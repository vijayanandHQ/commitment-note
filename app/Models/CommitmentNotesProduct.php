<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitmentNotesProduct extends Model
{
    use HasFactory;

    protected $table = 'commitment_notes_product';
    protected $primaryKey = 'id';

    protected $fillable = [
        'commitment_notes_id',
        'product_name',
        'quantity',
        'mrp',
        'total_price',
        'supplier_id',
        'order_qty',
        'remarks',
        'received_status',
        'contacted_status',
        'delivered_status',
        'returned_status',
        'ns_status',
    ];

    protected $casts = [
        'quantity'         => 'integer',
        'order_qty'        => 'integer',
        'supplier_id'      => 'integer',
        'received_status'  => 'integer',
        'contacted_status' => 'integer',
        'delivered_status' => 'integer',
        'returned_status'  => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function commitmentNote()
    {
        return $this->belongsTo(CommitmentNote::class, 'commitment_notes_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}