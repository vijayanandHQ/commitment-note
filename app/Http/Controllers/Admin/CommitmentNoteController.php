<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitmentNote;
use App\Models\Medicine;
use App\Models\CommitmentNotesProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommitmentNoteController extends Controller
{
    public function index()
    {
        $notes  = CommitmentNote::with('user')->latest()->get();
        $stages = CommitmentNote::getWorkflowStages();
        return view('admin.commitment-notes.index', compact('notes', 'stages'));
    }

    public function searchSuppliers(Request $request)
    {
        $query     = $request->get('query');
        $suppliers = \App\Models\Supplier::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->select('id', 'name')
            ->limit(10)
            ->get();
        return response()->json($suppliers);
    }

    public function updateProductDetails(Request $request, $id)
    {
        try {
            $product = CommitmentNotesProduct::findOrFail($id);

            $product->order_qty   = $request->input('qty');
            $product->supplier_id = $request->input('supplier_id') ?: null;
            $product->remarks     = $request->input('remarks');
            $product->save();

            return response()->json(['success' => true, 'message' => 'Updated Successfully!']);
        } catch (\Exception $e) {
            Log::error('updateProductDetails error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function create()
{
    // Show only products that are still pending (not delivered, not returned, not NS)
    // i.e., delivered_status=1 AND returned_status=1 AND ns_status=1
    $recentCommitments = CommitmentNote::with(['products' => function($query) {
            $query->where('delivered_status', 1)
                  ->where('returned_status', 1)
                  ->where('ns_status', 1)
                  ->with('supplier');
        }])
        ->whereHas('products', function($query) {
            $query->where('delivered_status', 1)
                  ->where('returned_status', 1)
                  ->where('ns_status', 1);
        })
        ->latest()
        ->limit(10)
        ->get();

    return view('admin.commitment-notes.create', compact('recentCommitments'));
}
    public function allRecords()
    {
        $notes = CommitmentNote::latest()->get();
        return view('admin.commitment-notes.all', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cus_name'           => 'nullable|string|max:255',
            'customer_phone'     => 'required|string|max:20',
            'delivery_date'      => 'nullable|string',
            'delivery_type'      => 'nullable|in:home,medical',
            'commands'           => 'nullable|string',
            'sales_person_name' => 'nullable|string|max:255',
            'advance_amount'    => 'nullable|numeric|min:0',
            'products'           => 'required|array|min:1',
        ]);

        $products = $request->input('products', []);

        $validProducts = [];
        $productNames  = [];
        $totalQty      = 0;
        $totalMrp      = 0;
        $suppliers     = [];

        foreach ($products as $index => $product) {
            $productName = trim($product['product_name'] ?? '');
            $orderQty    = (int)($product['order_qty'] ?? 0);
            $mrp         = (float)($product['mrp'] ?? 0);

            if (empty($productName) && $orderQty <= 0) {
                continue;
            }

            if (!empty($productName)) {
                if ($orderQty < 1) $orderQty = 1;

                $validProducts[] = [
                    'product_name' => $productName,
                    'quantity'     => $orderQty,
                    'mrp'          => $mrp,
                    'total_price'  => $mrp * $orderQty,
                    'supplier_name'=> trim($product['supplier_name'] ?? ''),
                ];

                $productNames[] = $productName;
                $totalQty      += $orderQty;
                $totalMrp      += $mrp * $orderQty;

                if (!empty($product['supplier_name'])) {
                    $suppliers[] = trim($product['supplier_name']);
                }
            }
        }

        if (empty($validProducts)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['products' => 'At least one product is required.']);
        }

        $deliveryDate = null;
        if ($request->delivery_date) {
            $dateParts = explode('/', $request->delivery_date);
            if (count($dateParts) === 3) {
                $day      = str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
                $month    = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
                $year     = $dateParts[2];
                $fullYear = strlen($year) === 2 ? "20{$year}" : $year;
                $deliveryDate = "{$fullYear}-{$month}-{$day}";
            }
        }

        $combinedProductNames = implode(', ', $productNames);
        $uniqueSuppliers      = array_unique($suppliers);
        $combinedSuppliers    = implode(', ', $uniqueSuppliers);

        $commitmentNote = CommitmentNote::create([
            'date'               => now()->format('Y-m-d'),
            'qty'                => $totalQty,
            'product_name'       => $combinedProductNames,
            'mrp'                => $totalMrp,
            'order_qty'          => $totalQty,
            'supplier'           => $combinedSuppliers,
            'customer_phone'     => $request->customer_phone,
            'cus_name'           => $request->cus_name,
            'delivery_date'      => $deliveryDate,
            'delivery_type'      => $request->delivery_type,
            'comments'           => $request->commands,
            'sales_person_name' => $request->sales_person_name,
            'advance_amount'    => $request->advance_amount,
            'created_by'         => auth()->id(),
            'workflow_stage'     => 'pending_supplier',
            'supplier_asked_at'  => now(),
        ]);

        foreach ($validProducts as $product) {
            CommitmentNotesProduct::create([
                'commitment_notes_id' => $commitmentNote->id,
                'product_name'        => $product['product_name'],
                'quantity'            => $product['quantity'],
                'mrp'                 => $product['mrp'],
                'total_price'         => $product['total_price'],
                'received_status'     => 1,
                'contacted_status'    => 1,
                'delivered_status'    => 1,
                'returned_status'     => 1,
            ]);
        }

        return redirect()->route('admin.commitment-notes.create')
            ->with('success', count($validProducts) . ' product(s) saved successfully into commitment note #' . $commitmentNote->id);
    }

    public function show(CommitmentNote $commitmentNote)
{
    $products = \App\Models\CommitmentNotesProduct::with('supplier')
        ->where('commitment_notes_id', $commitmentNote->id)
        ->get();

    return view('admin.commitment-notes.show', compact('commitmentNote', 'products'));
    // $singleProduct is NOT passed here, so it defaults to false in the blade
}
           public function edit(CommitmentNote $commitmentNote)
{
    // Fetch ALL products for this note (standard edit-all-products view)
    $products = \App\Models\CommitmentNotesProduct::with('supplier')
        ->where('commitment_notes_id', $commitmentNote->id)
        ->get();

    return view('admin.commitment-notes.edit', compact('commitmentNote', 'products'));
}

public function destroyProduct($id)
{
    try {
        // Find the product row
        $product = CommitmentNotesProduct::findOrFail($id);
        $commitmentNotesId = $product->commitment_notes_id;

        // Delete the product row
        $product->delete();

        // Check if any other products exist for the same commitment_notes_id
        $remainingCount = CommitmentNotesProduct::where('commitment_notes_id', $commitmentNotesId)->count();

        if ($remainingCount === 0) {
            // No other products — delete the parent commitment note too
            CommitmentNote::where('id', $commitmentNotesId)->delete();
            $message = 'Product and its commitment note deleted (no remaining products).';
        } else {
            $message = 'Product deleted successfully.';
        }

        return redirect()->route('admin.commitment-notes.create')
            ->with('success', $message);

    } catch (\Exception $e) {
        Log::error('destroyProduct error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
    }
}



        public function update(Request $request, CommitmentNote $commitmentNote)
{
    $request->validate([
        'cus_name'       => 'nullable|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'delivery_date'  => 'nullable|date',
        'delivery_type'  => 'nullable|in:home,medical',
        'comments'       => 'nullable|string',
    ]);

    // Update parent commitment note (customer details)
    $commitmentNote->update([
        'cus_name'       => $request->input('cus_name'),
        'customer_phone' => $request->input('customer_phone'),
        'delivery_date'  => $request->input('delivery_date') ?: null,
        'delivery_type'  => $request->input('delivery_type'),
        'comments'       => $request->input('comments'),
    ]);

    // Update each submitted product row
    $products = $request->input('products', []);

    foreach ($products as $productId => $data) {
        // Security: only update products that actually belong to this note
        $product = \App\Models\CommitmentNotesProduct::where('id', $productId)
            ->where('commitment_notes_id', $commitmentNote->id)
            ->first();

        if (!$product) continue;

        $qty   = isset($data['quantity'])   ? (int)   $data['quantity']   : $product->quantity;
        $mrp   = isset($data['mrp'])        ? (float) $data['mrp']        : $product->mrp;
        $total = $qty * $mrp;

        $product->product_name = $data['product_name'] ?? $product->product_name;
        $product->quantity     = $qty;
        $product->mrp          = $mrp;
        $product->total_price  = $total;
        $product->order_qty    = isset($data['order_qty'])   ? (int) $data['order_qty']   : $product->order_qty;
        $product->supplier_id  = (isset($data['supplier_id']) && $data['supplier_id'] !== '')
                                    ? (int) $data['supplier_id'] : null;
        $product->remarks      = $data['remarks'] ?? $product->remarks;

        if (isset($data['received_status']))  $product->received_status  = (int) $data['received_status'];
        if (isset($data['contacted_status'])) $product->contacted_status = (int) $data['contacted_status'];
        if (isset($data['delivered_status'])) $product->delivered_status = (int) $data['delivered_status'];
        if (isset($data['returned_status']))  $product->returned_status  = (int) $data['returned_status'];

        $product->save();
    }

    // Recalculate workflow_stage on parent note from ALL its products
    $allProducts = $commitmentNote->products()->get();

    $anyReturned  = $allProducts->contains(fn($p) => $p->returned_status  == 0);
    $allDelivered = $allProducts->every(fn($p)    => $p->delivered_status == 0);
    $allContacted = $allProducts->every(fn($p)    => $p->contacted_status == 0);
    $allReceived  = $allProducts->every(fn($p)    => $p->received_status  == 0);

    if ($anyReturned)      $newStage = 'returned';
    elseif ($allDelivered) $newStage = 'delivered';
    elseif ($allContacted) $newStage = 'customer_contacted';
    elseif ($allReceived)  $newStage = 'received_from_supplier';
    else                   $newStage = 'pending_supplier';

    $commitmentNote->update(['workflow_stage' => $newStage]);

    return redirect()->route('admin.commitment-notes.create')
        ->with('success', 'Commitment note #' . $commitmentNote->id . ' updated successfully!');
}

public function editByProduct($productId)
{
    // Fetch ONLY this specific product by commitment_notes_product.id
    $product = \App\Models\CommitmentNotesProduct::with('supplier')
        ->findOrFail($productId);

    // Get parent commitment note via commitment_notes_id
    $commitmentNote = \App\Models\CommitmentNote::findOrFail($product->commitment_notes_id);

    // Wrap in collection so the blade @forelse loop works unchanged
    $products = collect([$product]);

    return view('admin.commitment-notes.edit', compact('commitmentNote', 'products'));
}

public function showByProduct($productId)
{
    // Fetch ONLY this specific product by commitment_notes_product.id
    $product = \App\Models\CommitmentNotesProduct::with('supplier')
        ->findOrFail($productId);

    // Get parent commitment note
    $commitmentNote = \App\Models\CommitmentNote::findOrFail($product->commitment_notes_id);

    // Wrap in collection so the blade @forelse loop works unchanged
    $products = collect([$product]);

    // $singleProduct = true tells the blade to show the filter notice
    // and "All Products" / "Edit Product" buttons
    return view('admin.commitment-notes.show', compact('commitmentNote', 'products'))
        ->with('singleProduct', true);
}

    public function destroy(CommitmentNote $commitmentNote)
    {
        $commitmentNote->delete();
        return redirect()->route('admin.commitment-notes.index')
            ->with('success', 'Commitment note deleted successfully!');
    }

    /**
     * Update a single product's status column.
     * Sets the column to 0 and recalculates the parent note's workflow_stage.
     */
    public function updateProductStatus(Request $request, $productId)
{
    $request->validate([
        'status_field' => 'required|in:received_status,contacted_status,delivered_status,returned_status,ns_status',
    ]);

    try {
        $product = CommitmentNotesProduct::findOrFail($productId);

        if ($product->{$request->status_field} == 0) {
            return response()->json(['success' => false, 'message' => 'Already marked.'], 422);
        }

        $product->{$request->status_field} = 0;
        $product->save();

        // Calculate the NEW stage info for this specific product to send back to JS
        if ($product->returned_status == 0) {
            $stageInfo = ['bg' => 'danger', 'icon' => 'bx-undo', 'text' => 'Returned'];
        } elseif ($product->delivered_status == 0) {
            $stageInfo = ['bg' => 'success', 'icon' => 'bx-check-circle', 'text' => 'Delivered'];
        } elseif ($product->contacted_status == 0) {
            $stageInfo = ['bg' => 'primary', 'icon' => 'bx-phone-call', 'text' => 'Contacted'];
        } elseif ($product->received_status == 0) {
            $stageInfo = ['bg' => 'info', 'icon' => 'bx-package', 'text' => 'Received'];
        } else {
            $stageInfo = ['bg' => 'warning', 'icon' => 'bx-time', 'text' => 'Pending'];
        }

        // Logic for updating parent CommitmentNote (Workflow Stage)
        $note = $product->commitmentNote;
        $allProducts = $note->products()->get();

        $anyReturned  = $allProducts->contains(fn($p) => $p->returned_status == 0);
        $allDelivered = $allProducts->every(fn($p) => $p->delivered_status == 0);
        $allContacted = $allProducts->every(fn($p) => $p->contacted_status == 0);
        $allReceived  = $allProducts->every(fn($p) => $p->received_status == 0);

        if ($anyReturned) $newStage = 'returned';
        elseif ($allDelivered) $newStage = 'delivered';
        elseif ($allContacted) $newStage = 'customer_contacted';
        elseif ($allReceived)  $newStage = 'received_from_supplier';
        else                   $newStage = 'pending_supplier';

        $note->update(['workflow_stage' => $newStage]);

       return response()->json([
    'success'    => true,
    'message'    => 'Status updated successfully.',
    'stage_info' => $stageInfo,
    'new_stage'  => $newStage,
    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
]);

    } catch (\Exception $e) {
        Log::error('updateProductStatus error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function searchMedicines(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $medicines = Medicine::where('is_active', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', $query . '%')
                      ->orWhere('name', 'LIKE', '% ' . $query . '%');
                })
                ->select('id', 'name', 'price', 'stock_quantity', 'supplier_name')
                ->limit(10)
                ->get();

            return response()->json($medicines);
        } catch (\Exception $e) {
            Log::error('Medicine search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    public function getCategories()
    {
        $categories = DB::table('medicines')
            ->select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();

        return response()->json($categories);
    }

    public function searchWithDetails(Request $request)
    {
        try {
            $query         = $request->get('query');
            $categories    = $request->get('categories');
            $startsWith    = $request->get('starts_with', false);
            $categoryArray = $categories ? explode(',', $categories) : [];

            $medicinesQuery = Medicine::query();

            $medicinesQuery->where(function ($q) use ($query, $startsWith) {
                if ($startsWith) {
                    $q->where('name', 'LIKE', "{$query}%")
                      ->orWhere('generic_name', 'LIKE', "{$query}%")
                      ->orWhere('manufacturer', 'LIKE', "{$query}%")
                      ->orWhere('product_code', 'LIKE', "{$query}%")
                      ->orWhere('supplier_name', 'LIKE', "{$query}%");
                } else {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('generic_name', 'LIKE', "%{$query}%")
                      ->orWhere('manufacturer', 'LIKE', "%{$query}%")
                      ->orWhere('product_code', 'LIKE', "%{$query}%")
                      ->orWhere('supplier_name', 'LIKE', "%{$query}%");
                }
            });

            if (!empty($categoryArray)) {
                $medicinesQuery->whereIn('category', $categoryArray);
            }

            $medicines = $medicinesQuery->where('is_active', true)->orderBy('name')->limit(20)->get();

            $formattedMedicines = $medicines->map(fn($m) => [
                'id'             => $m->id,
                'name'           => $m->name,
                'generic_name'   => $m->generic_name   ?? '',
                'manufacturer'   => $m->manufacturer   ?? '',
                'product_code'   => $m->product_code   ?? '',
                'price'          => $m->price          ?? 0,
                'mrp'            => $m->price          ?? 0,
                'stock_quantity' => $m->stock_quantity ?? 0,
                'category'       => $m->category       ?? 'General',
                'supplier_name'  => $m->supplier_name  ?? 'No Supplier',
            ]);

            return response()->json($formattedMedicines);
        } catch (\Exception $e) {
            Log::error('Error searching medicines: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
