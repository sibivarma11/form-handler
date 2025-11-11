<?php

namespace SibiVarma\FormSubmissions\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'data',
        'ip_address',
        'user_agent',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];
}