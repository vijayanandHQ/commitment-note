<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\ProductSupplier;
use App\Models\Batch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use SkipsErrors, SkipsFailures;

    protected $successCount = 0;
    protected $skippedRows = [];
    protected $categories = [];
    protected $units = [];
    protected $suppliers = [];

    public function __construct()
    {
        // Load existing data for performance
        $this->categories = Category::pluck('id', 'name')->toArray();
        $this->units = Unit::pluck('id', 'name')->toArray();
        $this->suppliers = Supplier::whereNotNull('supplier_code')
            ->pluck('id', 'supplier_code')
            ->toArray();
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty(array_filter($row))) {
            $this->skippedRows[] = $row;
            return null;
        }

        // Get product name (required)
        $productName = $row['productname'] ?? $row['product_name'] ?? $row['product name'] ?? null;
        if (empty($productName)) {
            $this->skippedRows[] = $row;
            return null;
        }

        $productName = trim($productName);
        
        // Skip summary rows
        if (str_contains(strtolower($productName), 'grand total') || 
            str_contains(strtolower($productName), 'total')) {
            $this->skippedRows[] = $row;
            return null;
        }

        // Start database transaction
        DB::beginTransaction();
        
        try {
            // ============= 1. HANDLE CATEGORY =============
            $categoryName = $row['categorey'] ?? $row['category'] ?? 'General';
            $categoryName = trim($categoryName);
            
            if (!isset($this->categories[$categoryName])) {
                $category = Category::create(['name' => $categoryName]);
                $this->categories[$categoryName] = $category->id;
            }
            $categoryId = $this->categories[$categoryName];

            // ============= 2. HANDLE UNIT =============
            // Determine unit name
            $purchaseUnit = $row['purchaseunit'] ?? $row['purchase_unit'] ?? 1;
            $saleUnit = $row['saleunit'] ?? $row['sale_unit'] ?? 1;
            
            // Create unit name based on values
            if ($purchaseUnit == $saleUnit) {
                $unitName = $purchaseUnit . ' unit';
            } else {
                $unitName = 'piece'; // Default
            }
            
            if (!isset($this->units[$unitName])) {
                $unit = Unit::create(['name' => $unitName]);
                $this->units[$unitName] = $unit->id;
            }
            $unitId = $this->units[$unitName];

            // ============= 3. HANDLE SUPPLIER =============
            $supplierName = $row['supplier_name'] ?? $row['suppliername'] ?? $row['supplier name'] ?? null;
            $supplierCode = $row['supplier_code'] ?? $row['suppliercode'] ?? $row['supplier code'] ?? null;
            $supplierId = null;
            
            if ($supplierName || $supplierCode) {
                // Try to find by code first
                if ($supplierCode && isset($this->suppliers[$supplierCode])) {
                    $supplierId = $this->suppliers[$supplierCode];
                }
                
                // If not found by code, try by name or create new
                if (!$supplierId && $supplierName) {
                    $supplier = Supplier::firstOrCreate(
                        ['name' => $supplierName],
                        [
                            'company_name' => $supplierName,
                            'phone' => '0000000000', // Placeholder
                            'supplier_code' => $supplierCode,
                            'is_active' => 1,
                        ]
                    );
                    $supplierId = $supplier->id;
                    
                    // Cache for future rows
                    if ($supplier->supplier_code) {
                        $this->suppliers[$supplier->supplier_code] = $supplierId;
                    }
                }
            }

            // ============= 4. HANDLE PRODUCT =============
            $productCode = $row['productcode'] ?? $row['product_code'] ?? null;
            
            // Check if product already exists
            $existingProduct = Product::where('name', $productName)
                ->orWhere('product_code', $productCode)
                ->first();
                
            if ($existingProduct) {
                DB::rollBack();
                $this->skippedRows[] = $row;
                return null;
            }

            // Parse prices
            $mrp = $this->parsePrice($row['mrp'] ?? 0);
            $purchasePrice = $this->parsePrice($row['purchase_price'] ?? $row['purchaseprice'] ?? 0);
            
            // Create product
            $product = Product::create([
                'product_code' => $productCode,
                'name' => $productName,
                'generic_name' => $row['generic_name'] ?? $row['genericname'] ?? null,
                'category_id' => $categoryId,
                'sale_unit_id' => $unitId,
                'mrp' => $mrp,
                'is_active' => 1,
            ]);

            // ============= 5. HANDLE PRODUCT-SUPPLIER RELATIONSHIP =============
            if ($supplierId) {
                // Check if relationship already exists
                $existingRelation = ProductSupplier::where('product_id', $product->id)
                    ->where('supplier_id', $supplierId)
                    ->first();
                
                if (!$existingRelation) {
                    ProductSupplier::create([
                        'product_id' => $product->id,
                        'supplier_id' => $supplierId,
                        'purchase_unit_id' => $unitId,
                        'purchase_price' => $purchasePrice ?: ($mrp * 0.8),
                    ]);
                }

                // ============= 6. CREATE INITIAL BATCH =============
                Batch::create([
                    'product_id' => $product->id,
                    'supplier_id' => $supplierId,
                    'batch_no' => 'BATCH-' . time() . '-' . $product->id,
                    'expiry_date' => now()->addYears(2), // Default 2 years from now
                    'mrp' => $mrp,
                    'purchase_price' => $purchasePrice ?: ($mrp * 0.8),
                    'quantity' => 0, // Initial stock 0
                ]);
            }

            // ============= 7. ALSO UPDATE MEDICINES TABLE (if you want to keep it in sync) =============
            // This is optional - if you want to keep the medicines table updated as well
            if (DB::connection()->getDatabaseName()) {
                try {
                    DB::table('medicines')->insert([
                        'name' => $productName,
                        'generic_name' => $row['generic_name'] ?? $row['genericname'] ?? null,
                        'generic_name_original' => $row['generic_name'] ?? $row['genericname'] ?? null,
                        'manufacturer' => $supplierName,
                        'supplier_code' => $supplierCode,
                        'supplier_name' => $supplierName,
                        'alt_supplier_codes' => $row['alt_supplier_code'] ?? $row['altsuppliercode'] ?? null,
                        'price' => $mrp,
                        'purchase_price' => $purchasePrice,
                        'purchase_unit' => $purchaseUnit,
                        'sale_unit' => $saleUnit,
                        'stock_quantity' => 0,
                        'category' => $categoryName,
                        'unit' => $unitName,
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Log but don't fail if medicines table insert fails
                    Log::warning('Failed to insert into medicines table: ' . $e->getMessage());
                }
            }

            DB::commit();
            $this->successCount++;
            
            return $product;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import row failed: ' . $e->getMessage(), [
                'row' => $row,
                'trace' => $e->getTraceAsString()
            ]);
            $this->skippedRows[] = $row;
            return null;
        }
    }

    /**
     * Parse price from various formats
     */
    protected function parsePrice($value)
    {
        if (empty($value)) return 0;
        if (is_numeric($value)) return (float) $value;
        
        // Remove currency symbols and commas
        $value = preg_replace('/[^0-9.]/', '', (string) $value);
        return (float) $value ?: 0;
    }

    /**
     * Batch size for memory management
     */
    public function batchSize(): int
    {
        return 50;
    }

    /**
     * Chunk size for memory management
     */
    public function chunkReading(): int
    {
        return 50;
    }

    /**
     * Get success count
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     * Get skipped rows
     */
    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
}