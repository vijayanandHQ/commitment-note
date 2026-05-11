<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'brand_name',
        'category',
        'price',
        'mrp',
        'stock_quantity',
        'supplier_id',
        'status',
        'description',
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