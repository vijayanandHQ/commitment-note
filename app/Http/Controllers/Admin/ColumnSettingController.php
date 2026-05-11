<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ColumnSetting;
use Illuminate\Http\Request;

class ColumnSettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $defaultColumns = [
            'sno' => 'S.No',
            'date' => 'Date',
            'qty' => 'Qty',
            'product_name' => 'Product Name',
            'mrp' => 'MRP',
            'order_qty' => 'Order Qty',
            'supplier' => 'Supplier',
            'customer_phone' => 'Customer Phone',
            'cus_name' => 'Customer Name',
            'delivery_date' => 'Delivery Date',
            'status' => 'Status',
            'comments' => 'Comments',
        ];
        
        $settings = ColumnSetting::where('user_id', $user->id)->get();
        
        // If no settings exist, create default ones
        if ($settings->isEmpty()) {
            foreach ($defaultColumns as $key => $name) {
                ColumnSetting::create([
                    'user_id' => $user->id,
                    'column_name' => $key,
                    'display_name' => $name,
                    'is_visible' => true,
                    'order' => array_search($key, array_keys($defaultColumns)),
                    'is_custom' => false,
                ]);
            }
            $settings = ColumnSetting::where('user_id', $user->id)->get();
        }
        
        return view('admin.column-settings.index', compact('defaultColumns', 'settings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $allColumns = $request->input('all_columns', []); // Get all possible columns
        $checkedColumns = $request->input('columns', []); // Get only checked columns
        
        // Update visibility for all columns
        foreach ($allColumns as $column) {
            $isVisible = isset($checkedColumns[$column]);
            
            ColumnSetting::updateOrCreate(
                ['user_id' => $user->id, 'column_name' => $column],
                [
                    'is_visible' => $isVisible,
                    'is_custom' => false
                ]
            );
        }
        
        // Handle custom column addition
        if ($request->has('new_column_name') && !empty($request->new_column_name)) {
            $newColumnName = strtolower(str_replace(' ', '_', trim($request->new_column_name)));
            $displayName = trim($request->new_column_name);
            
            if ($newColumnName && $displayName) {
                ColumnSetting::create([
                    'user_id' => $user->id,
                    'column_name' => $newColumnName,
                    'display_name' => $displayName,
                    'is_visible' => true,
                    'is_custom' => true,
                    'order' => 999,
                ]);
            }
        }
        
        return redirect()->route('admin.column-settings.index')
                         ->with('success', 'Column settings updated successfully!');
    }

    public function deleteCustomColumn(Request $request, $id)
    {
        $setting = ColumnSetting::findOrFail($id);
        
        if ($setting->user_id !== auth()->id() || !$setting->is_custom) {
            abort(403);
        }
        
        $setting->delete();
        
        return redirect()->route('admin.column-settings.index')
                         ->with('success', 'Custom column deleted successfully!');
    }
}