<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    // Specify the table name explicitly
    protected $table = 'staffs'; // Use your actual table name

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'password', // Add password field
        'balance',
        'photo',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function staffTasks()
    {
        return $this->hasMany(StaffTask::class, 'staff_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'staff_tasks')
                    ->withPivot(['status', 'assigned_at', 'started_at', 'completed_at', 'proof_description', 'proof_photos', 'notes'])
                    ->withTimestamps();
    }

    public function getCompletedTasksCountAttribute()
    {
        return $this->staffTasks()->where('status', 'completed')->count();
    }

    public function getAssignedTasksCountAttribute()
    {
        return $this->staffTasks()->where('status', 'assigned')->count();
    }

    public function getInProgressTasksCountAttribute()
    {
        return $this->staffTasks()->where('status', 'in_progress')->count();
    }
}