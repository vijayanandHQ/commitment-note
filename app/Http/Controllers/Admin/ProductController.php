<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'saleUnit']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('name')->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'units', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'nullable|string|max:50|unique:products',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sale_unit_id' => 'nullable|exists:units,id',
            'mrp' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        // Handle supplier relationship if provided
        if ($request->has('supplier_id') && $request->supplier_id) {
            $product->suppliers()->attach($request->supplier_id, [
                'purchase_price' => $request->purchase_price ?? $product->mrp * 0.8,
                'purchase_unit_id' => $request->purchase_unit_id ?? $request->sale_unit_id,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'saleUnit', 'suppliers', 'batches' => function($q) {
            $q->orderBy('expiry_date');
        }]);
        
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'units', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'nullable|string|max:50|unique:products,product_code,' . $product->id,
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sale_unit_id' => 'nullable|exists:units,id',
            'mrp' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function showImportForm()
    {
        return view('admin.products.import');
    }

  public function import(Request $request)
{
    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $import = new ProductsImport();
        Excel::import($import, $request->file('import_file'));

        $successCount = $import->getSuccessCount();
        $skippedCount = count($import->getSkippedRows());

        $message = "✅ Import completed successfully!";
        $message .= "\n• {$successCount} products imported";
        
        if ($skippedCount > 0) {
            $message .= "\n• {$skippedCount} rows skipped (duplicates or invalid data)";
        }

        // Get failures if any
        if (method_exists($import, 'failures') && count($import->failures()) > 0) {
            $message .= "\n• " . count($import->failures()) . " validation failures";
        }

        return redirect()->route('admin.products.index')
            ->with('success', nl2br($message));

    } catch (\Exception $e) {
        Log::error('Import failed: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Import failed: ' . $e->getMessage());
    }
}

    public function downloadTemplate()
    {
        $headers = [
            'ProductCode',
            'ProductName',
            'GENERIC Name',
            'CATEGOREY',
            'Supplier Name',
            'Supplier Code',
            'PurchaseUnit',
            'SaleUnit',
            'MRP',
            'Purchase Price',
        ];

        $filename = 'product_import_template.csv';
        
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, $headers);
        fputcsv($handle, [
            '2249',
            '10ML DISPOVAN SYRINGE',
            '',
            'SURGICAL',
            'KR.P.AGENCIES',
            '305',
            '1',
            '1',
            '13',
            '4.5',
        ]);
        
        fclose($handle);
        exit;
    }
}