<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    // Поля, которые можно массово заполнять
    protected $fillable = [
        'name',
        'description',
        'blade_template',
    ];

    // Отношение "один ко многим" с переменными блока
    public function variables()
    {
        return $this->hasMany(BlockVariable::class);
    }
}
