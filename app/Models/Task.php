<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'due_date',
        'priority',
        'requires_proof',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'requires_proof' => 'boolean',
    ];

    // Relationship to staff through staff_tasks pivot table
    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'staff_tasks')
                    ->withPivot(['status', 'assigned_at', 'started_at', 'completed_at', 'proof_description', 'proof_photos', 'notes'])
                    ->withTimestamps();
    }

    public function staffTasks()
    {
        return $this->hasMany(StaffTask::class);
    }

    public function getAssignedCountAttribute()
    {
        return $this->staffTasks()->count();
    }

    public function getCompletedCountAttribute()
    {
        return $this->staffTasks()->where('status', 'completed')->count();
    }

    public function getIsPenaltyAttribute()
    {
        return $this->amount < 0;
    }

    public function getIsBonusAttribute()
    {
        return $this->amount > 0;
    }
}