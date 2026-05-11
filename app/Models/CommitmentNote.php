<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitmentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'qty', 'product_name', 'mrp', 'order_qty', 'supplier',
        'customer_phone', 'cus_name', 'delivery_date', 'status', 'comments',
        'created_by', 'workflow_stage', 'delivery_type', 'supplier_asked_at',
        'received_at', 'customer_contacted_at', 'delivered_at', 'sales_person_name', 'advance_amount'
    ];

    protected $casts = [
        'date' => 'date',
        'delivery_date' => 'date',
        'supplier_asked_at' => 'datetime',
        'received_at' => 'datetime',
        'customer_contacted_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public static function getWorkflowStages()
    {
        return [
            'pending_supplier' => [
                'name' => 'Ask Supplier',
                'icon' => 'bx-message-square-dots',
                'color' => 'warning',
                'order' => 1
            ],
            'received_from_supplier' => [
                'name' => 'Received',
                'icon' => 'bx-package',
                'color' => 'info',
                'order' => 2
            ],
            'customer_contacted' => [
                'name' => 'Contacted',
                'icon' => 'bx-phone-call',
                'color' => 'primary',
                'order' => 3
            ],
            'delivered' => [
                'name' => 'Delivered',
                'icon' => 'bx-check-circle',
                'color' => 'success',
                'order' => 4
            ],
            'returned' => [
                'name' => 'Returned',
                'icon' => 'bx-undo',
                'color' => 'danger',
                'order' => 5
            ]
        ];
    }

    public function getStageOrder()
    {
        $stages = self::getWorkflowStages();
        return $stages[$this->workflow_stage]['order'] ?? 1;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function products()
{
    return $this->hasMany(CommitmentNotesProduct::class, 'commitment_notes_id');
}
}