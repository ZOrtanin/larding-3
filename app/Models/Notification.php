<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'url',
        'is_read',
        'payload',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'payload' => 'array',
    ];
}
