<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'task_id',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'proof_description',
        'proof_photos',
        'notes',
    ];

    protected $casts = [
        'proof_photos' => 'array',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getAmountAttribute()
    {
        return $this->task->amount;
    }

    public function completeWithProof($proofPhotos, $description = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'proof_photos' => $proofPhotos,
            'proof_description' => $description,
        ]);

        // Add amount to staff balance
        $this->staff->increment('balance', $this->task->amount);
    }
}