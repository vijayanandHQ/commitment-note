<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumnSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'column_name',
        'display_name',
        'is_visible',
        'is_custom',
        'order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}