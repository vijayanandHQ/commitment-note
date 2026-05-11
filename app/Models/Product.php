<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'generic_name',
        'category_id',
        'sale_unit_id',
        'mrp',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
                    ->withPivot('purchase_price', 'purchase_unit_id')
                    ->withTimestamps();
    }

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->batches()->sum('quantity');
    }
}