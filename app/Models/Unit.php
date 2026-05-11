<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function productsForSale()
    {
        return $this->hasMany(Product::class, 'sale_unit_id');
    }

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class, 'purchase_unit_id');
    }
}