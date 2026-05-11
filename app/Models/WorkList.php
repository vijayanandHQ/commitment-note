<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkList extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_name',
        'photo',
        'tasks',
        'task_status',
        'upload_date',
        'ppt_file',
        'is_editable',
    ];

    protected $casts = [
        'task_status' => 'array',
        'upload_date' => 'date',
        'is_editable' => 'boolean',
    ];

    // Add accessor for completed tasks count
    public function getCompletedTasksCountAttribute()
    {
        if (!$this->task_status) {
            return 0;
        }

        $completed = 0;
        foreach ($this->task_status as $task => $status) {
            if ($status) {
                $completed++;
            }
        }
        return $completed;
    }

    // Add accessor for total tasks count
    public function getTotalTasksCountAttribute()
    {
        if (!$this->task_status) {
            return 0;
        }
        return count($this->task_status);
    }

    // Add method to mark task as complete
    public function markTaskAsComplete($taskName)
    {
        if (!$this->task_status) {
            return false;
        }

        if (isset($this->task_status[$taskName])) {
            $this->task_status[$taskName] = true;
            $this->save();
            return true;
        }
        return false;
    }

    // Add method to mark task as incomplete
    public function markTaskAsIncomplete($taskName)
    {
        if (!$this->task_status) {
            return false;
        }

        if (isset($this->task_status[$taskName])) {
            $this->task_status[$taskName] = false;
            $this->save();
            return true;
        }
        return false;
    }
}