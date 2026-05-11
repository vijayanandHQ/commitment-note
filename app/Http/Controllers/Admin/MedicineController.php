<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicineImportRequest;
use App\Imports\MedicinesImport;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines.
     */
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        
        // Allow sorting by these fields only
        $allowedSortFields = [
            'id', 'product_code', 'name', 'generic_name', 'category', 
            'supplier_name', 'supplier_code', 'price', 'purchase_price',
            'purchase_unit', 'sale_unit', 'stock_quantity', 'is_active', 'created_at'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $medicines = $query->paginate(20)->withQueryString();
        
        // Get unique categories for filter dropdown
        $categories = Medicine::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->orderBy('category')
            ->pluck('category');

        return view('admin.medicines.index', compact('medicines', 'categories', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        // Get suppliers for dropdown
        $suppliers = Supplier::where('is_active', true)
            ->orderBy('company_name')
            ->get(['id', 'company_name', 'name', 'supplier_code']);
        
        return view('admin.medicines.create');
    }

    /**
     * Store a newly created medicine in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'generic_name'      => 'nullable|string|max:255',
        'generic_name_original' => 'nullable|string|max:255',
        'manufacturer'      => 'nullable|string|max:255',
        'price'             => 'required|numeric|min:0',
        'purchase_price'    => 'nullable|numeric|min:0',
        'stock_quantity'    => 'required|integer|min:0',
        'category'          => 'nullable|string|max:255',
        'unit'              => 'nullable|string|max:50',
        'expiry_date'       => 'nullable|date',
        'description'       => 'nullable|string',
        'product_code'      => 'nullable|string|max:50|unique:medicines,product_code',
        'supplier_name'     => 'nullable|string|max:255',
        'supplier_code'     => 'nullable|string|max:50',
        'purchase_unit'     => 'nullable|integer|min:1',
        'sale_unit'         => 'nullable|integer|min:1',
        'alt_supplier_codes'=> 'nullable|string',
        'is_active'         => 'sometimes|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') ? 1 : 0;

    if (empty($validated['product_code'])) {
        $validated['product_code'] = $this->generateProductCode();
    }

    Medicine::create($validated);

    return redirect()->route('admin.medicines.index')
                     ->with('success', 'Medicine added successfully!');
}
    /**
     * Display the specified medicine.
     */
    public function show(Medicine $medicine)
    {
        return view('admin.medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function edit(Medicine $medicine)
    {
        // Get suppliers for dropdown
        $suppliers = Supplier::where('is_active', true)
            ->orderBy('company_name')
            ->get(['id', 'company_name', 'name', 'supplier_code']);
        
        return view('admin.medicines.edit', compact('medicine', 'suppliers'));
    }

    /**
     * Update the specified medicine in storage.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'product_code' => 'nullable|string|max:50|unique:medicines,product_code,' . $medicine->id,
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_code' => 'nullable|string|max:50',
            'purchase_unit' => 'nullable|integer|min:1',
            'sale_unit' => 'nullable|integer|min:1',
            'alt_supplier_codes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // If supplier is selected from dropdown, get supplier details
        if (!empty($validated['supplier_id'])) {
            $supplier = Supplier::find($validated['supplier_id']);
            if ($supplier) {
                $validated['supplier_name'] = $supplier->company_name ?: $supplier->name;
                $validated['supplier_code'] = $supplier->supplier_code;
            }
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $medicine->update($validated);

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Medicine updated successfully!');
    }

    /**
     * Remove the specified medicine from storage.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Medicine deleted successfully!');
    }

    /**
     * Show import form
     */
    public function showImportForm(Request $request)
    {
        $query = Medicine::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = [
            'id', 'product_code', 'name', 'generic_name', 'category', 
            'supplier_name', 'supplier_code', 'price', 'purchase_price',
            'purchase_unit', 'sale_unit', 'stock_quantity', 'is_active', 'created_at'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $medicines = $query->paginate(20)->withQueryString();
        $categories = Medicine::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('admin.medicines.import', compact('medicines', 'categories', 'sortField', 'sortDirection'));
    }

    /**
     * Import medicines from Excel
     */
    public function import(MedicineImportRequest $request)
    {
        try {
            DB::beginTransaction();

            $import = new MedicinesImport();
            $file = $request->file('import_file');

            Excel::import($import, $file);

            DB::commit();

            $successCount = $import->getSuccessCount();
            $skippedCount = count($import->getSkippedRows());

            // Get sample of imported data to verify
            $sample = Medicine::latest()->take(3)->get();
            
            $message = "✅ Import completed!\n";
            $message .= "• {$successCount} new medicines imported\n";
            $message .= "• {$skippedCount} rows skipped\n\n";
            $message .= "Sample of imported data:\n";
            
            foreach ($sample as $med) {
                $message .= "• {$med->product_code} - {$med->name} (₹{$med->price})\n";
            }

            return redirect()->route('admin.medicines.import.form')
                ->with('success', nl2br($message));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'ProductCode',
            'ProductName',
            'CATEGOREY',
            'Supplier Name',
            'PurchaseUnit',
            'SaleUnit',
            'Supplier Code',
            'MRP',
            'Purchase Price',
            'Alt Supplier Code',
            'GENERIC Name',
        ];

        $filename = 'medicine_import_template.csv';
        
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, $headers);
        
        // Add sample row
        fputcsv($handle, [
            '2249',
            '10ML DISPOVAN SYRINGE',
            'SURGICAL',
            'KR.P.AGENCIES',
            '1',
            '1',
            '305',
            '13',
            '4.5',
            '79,17,80,55,65,305,323',
            ''
        ]);
        
        fclose($handle);
        exit;
    }

    /**
     * Get all categories with medicine counts
     */
    public function getCategories(Request $request)
    {
        try {
            // Get distinct categories with count from medicines table
            $categories = Medicine::select('category', DB::raw('count(*) as count'))
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->groupBy('category')
                ->orderBy('category')
                ->get();
            
            // If no categories found, return common categories with counts
            if ($categories->isEmpty()) {
                // Common medicine categories
                $commonCategories = ['Tablet', 'Capsule', 'Syrup', 'Injection', 'Cream', 'Drops', 'Inhaler', 'Surgical', 'General'];
                
                $categories = collect();
                foreach ($commonCategories as $cat) {
                    $count = Medicine::where('category', 'LIKE', "%{$cat}%")->count();
                    $categories->push((object)[
                        'category' => $cat,
                        'count' => $count
                    ]);
                }
            }
            
            return response()->json($categories);
            
        } catch (\Exception $e) {
            Log::error('Error loading categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load categories'], 500);
        }
    }

    /**
     * Search medicines with all details including supplier
     * Based on your actual database schema
     */
    /**
 * Search medicines with all details including supplier
 * Based on your actual database schema
 */
public function searchWithDetails(Request $request)
{
    try {
        $query = $request->get('query');
        $categories = $request->get('categories');
        $startsWith = $request->get('starts_with', false); // Get starts_with parameter
        
        // Parse categories if provided
        $categoryArray = [];
        if ($categories) {
            $categoryArray = explode(',', $categories);
        }
        
        Log::info('Searching medicines with query: ' . $query . ', categories: ' . json_encode($categoryArray) . ', starts_with: ' . $startsWith);
        
        // Build the query based on your actual database schema
        $medicinesQuery = Medicine::query();
        
        // Search in multiple fields - with starts_with logic
        $medicinesQuery->where(function($q) use ($query, $startsWith) {
            if ($startsWith) {
                // Search for names that START WITH the query
                $q->where('name', 'LIKE', "{$query}%")
                  ->orWhere('generic_name', 'LIKE', "{$query}%")
                  ->orWhere('manufacturer', 'LIKE', "{$query}%")
                  ->orWhere('product_code', 'LIKE', "{$query}%")
                  ->orWhere('supplier_name', 'LIKE', "{$query}%")
                  ->orWhere('supplier_code', 'LIKE', "{$query}%");
            } else {
                // Search for names that CONTAIN the query (anywhere)
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('generic_name', 'LIKE', "%{$query}%")
                  ->orWhere('manufacturer', 'LIKE', "%{$query}%")
                  ->orWhere('product_code', 'LIKE', "%{$query}%")
                  ->orWhere('supplier_name', 'LIKE', "%{$query}%")
                  ->orWhere('supplier_code', 'LIKE', "%{$query}%");
            }
        });
        
        // Apply category filter if selected
        if (!empty($categoryArray)) {
            $medicinesQuery->whereIn('category', $categoryArray);
        }
        
        // Only active medicines
        $medicinesQuery->where('is_active', true);
        
        // Order by name for better display
        $medicinesQuery->orderBy('name');
        
        // Get results with limit
        $medicines = $medicinesQuery->limit(20)->get();
        
        Log::info('Found ' . $medicines->count() . ' medicines');
        
        // Format the response based on your actual columns
        $formattedMedicines = $medicines->map(function($medicine) {
            return [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'generic_name' => $medicine->generic_name ?? '',
                'manufacturer' => $medicine->manufacturer ?? '',
                'product_code' => $medicine->product_code ?? '',
                'price' => $medicine->price ?? 0,
                'mrp' => $medicine->price ?? 0, // Using price as MRP since no separate MRP field
                'stock_quantity' => $medicine->stock_quantity ?? 0,
                'category' => $medicine->category ?? 'General',
                'supplier_name' => $medicine->supplier_name ?? 'No Supplier',
                'supplier_code' => $medicine->supplier_code ?? '',
            ];
        });
        
        return response()->json($formattedMedicines);
        
    } catch (\Exception $e) {
        Log::error('Error searching medicines: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Simple search for medicines (used in commitment notes)
     */
    public function searchMedicines(Request $request)
    {
        try {
            $query = $request->get('query');
            
            if (strlen($query) < 2) {
                return response()->json([]);
            }
            
            $medicines = Medicine::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('generic_name', 'LIKE', "%{$query}%")
                      ->orWhere('product_code', 'LIKE', "%{$query}%")
                      ->orWhere('supplier_name', 'LIKE', "%{$query}%");
                })
                ->select('id', 'name', 'price', 'stock_quantity', 'category', 'supplier_name', 'supplier_code')
                ->orderBy('name')
                ->limit(20)
                ->get();
            
            return response()->json($medicines);
            
        } catch (\Exception $e) {
            Log::error('Error in searchMedicines: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Generate a unique product code
     */
    private function generateProductCode()
    {
        $prefix = 'MED';
        $timestamp = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -4));
        
        $code = $prefix . $timestamp . $random;
        
        // Ensure uniqueness
        while (Medicine::where('product_code', $code)->exists()) {
            $random = strtoupper(substr(uniqid(), -4));
            $code = $prefix . $timestamp . $random;
        }
        
        return $code;
    }

    /**
     * Toggle medicine status
     */
    public function toggleStatus(Medicine $medicine)
    {
        $medicine->is_active = !$medicine->is_active;
        $medicine->save();
        
        return response()->json([
            'success' => true,
            'status' => $medicine->is_active ? 'active' : 'inactive'
        ]);
    }

    /**
     * Get low stock medicines
     */
    public function lowStock()
    {
        $medicines = Medicine::where('stock_quantity', '<', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->paginate(20);
        
        return view('admin.medicines.low-stock', compact('medicines'));
    }

    /**
     * Get expiring medicines
     */
    public function expiring()
    {
        $medicines = Medicine::where('expiry_date', '<=', now()->addMonths(3))
            ->where('expiry_date', '>=', now())
            ->where('is_active', true)
            ->orderBy('expiry_date')
            ->paginate(20);
        
        return view('admin.medicines.expiring', compact('medicines'));
    }

    /**
     * Get expired medicines
     */
    public function expired()
    {
        $medicines = Medicine::where('expiry_date', '<', now())
            ->where('is_active', true)
            ->orderBy('expiry_date', 'desc')
            ->paginate(20);
        
        return view('admin.medicines.expired', compact('medicines'));
    }

    /**
     * Bulk update stock
     */
    public function bulkStockUpdate(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:medicines,id',
            'updates.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($request->updates as $update) {
            $medicine = Medicine::find($update['id']);
            $medicine->stock_quantity = $update['quantity'];
            $medicine->save();
        }

        return response()->json(['success' => true, 'message' => 'Stock updated successfully']);
    }

    public function updatePrice(Request $request, Medicine $medicine)
{
    $request->validate([
        'price' => 'required|numeric|min:0',
    ]);

    $medicine->update(['price' => $request->price]);

    return response()->json([
        'success' => true,
        'new_price' => number_format($medicine->price, 2),
        'message' => 'Price updated successfully!'
    ]);
}
}