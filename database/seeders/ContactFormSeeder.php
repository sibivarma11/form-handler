<?php

namespace Database\Seeders;

use App\Models\ContactForm;
use Illuminate\Database\Seeder;

class ContactFormSeeder extends Seeder
{
    public function run()
    {
        ContactForm::create([
            'name' => 'General Contact',
            'slug' => 'general-contact',
            'description' => 'General contact form',
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true]
            ],
            'notification_email' => 'admin@example.com',
            'success_message' => 'Thank you for your message!',
            'submit_button_text' => 'Send Message',
            'is_active' => true
        ]);
    }
}