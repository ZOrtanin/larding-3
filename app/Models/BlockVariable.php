<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockVariable extends Model
{
    protected $fillable = [
        'block_id',
        'name',
        'label',
        'type',
        'default_value',
        'required',
    ];

    // Отношение "принадлежит блоку"
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
