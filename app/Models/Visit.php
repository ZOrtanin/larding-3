<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id',
        'ip',
        'method',
        'status_code',
        'user_agent',
        'browser',
        'platform',
        'device_type',
        'url',
        'referer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'is_mobile',
    ];

    protected $casts = [
        'is_mobile' => 'boolean',
        'status_code' => 'integer',
    ];
}
