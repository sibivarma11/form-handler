<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ContactForm extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'fields',
        'submit_button_text',
        'success_message',
        'is_active',
        'notification_email',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->name);
            }
        });
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function getNotificationEmailAttribute($value)
    {
        return $value ?? config('mail.from.address');
    }
}