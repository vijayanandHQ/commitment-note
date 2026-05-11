<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
    'product_code',
    'name',
    'generic_name',
    'generic_name_original',
    'manufacturer',
    'supplier_code',
    'supplier_name',
    'alt_supplier_codes',
    'price',
    'purchase_price',
    'purchase_unit',
    'sale_unit',
    'stock_quantity',
    'category',
    'unit',
    'expiry_date',
    'description',
    'is_active',
];

    /**
     * Get the supplier that owns the medicine
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Scope a query to only include active medicines
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to search by name
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('generic_name', 'LIKE', "%{$term}%")
              ->orWhere('brand_name', 'LIKE', "%{$term}%");
        });
    }
}