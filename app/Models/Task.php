<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'date',
        'time',
        'priority',
        'file_path',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'date' => 'date',
    ];
}
