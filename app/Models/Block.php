<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public const PLACEMENT_CONTENT = 'content';
    public const PLACEMENT_HEAD = 'head';
    public const PLACEMENT_BODY_START = 'body_start';
    public const PLACEMENT_BODY_END = 'body_end';
    public const PLACEMENT_FRONT_CSS = 'front_css';
    public const PLACEMENT_FRONT_JS = 'front_js';

    public const PLACEMENTS = [
        self::PLACEMENT_CONTENT,
        self::PLACEMENT_HEAD,
        self::PLACEMENT_BODY_START,
        self::PLACEMENT_BODY_END,
        self::PLACEMENT_FRONT_CSS,
        self::PLACEMENT_FRONT_JS,
    ];

    // Поля, которые можно массово заполнять
    protected $fillable = [
        'name',
        'description',
        'blade_template',
        'placement',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Отношение "один ко многим" с переменными блока
    public function variables()
    {
        return $this->hasMany(BlockVariable::class);
    }
}
