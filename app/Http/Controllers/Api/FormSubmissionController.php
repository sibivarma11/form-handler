<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\EmailSetting;
use App\Mail\FormSubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = FormSubmission::create([
            'data' => $request->all(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $recipient = EmailSetting::get('contact_form_recipient');
            
            if ($recipient) {
                config([
                    'mail.mailers.smtp.host' => EmailSetting::get('mail_host'),
                    'mail.mailers.smtp.port' => EmailSetting::get('mail_port'),
                    'mail.mailers.smtp.username' => EmailSetting::get('mail_username'),
                    'mail.mailers.smtp.password' => EmailSetting::get('mail_password'),
                    'mail.mailers.smtp.encryption' => EmailSetting::get('mail_encryption'),
                    'mail.from.address' => EmailSetting::get('mail_from_address'),
                    'mail.from.name' => EmailSetting::get('mail_from_name'),
                ]);
                
                Mail::to($recipient)->send(new FormSubmissionNotification($submission));
            }
        } catch (\Exception $e) {
            // Email sending failed silently
        }

        return response()->json([
            'message' => 'Thank you! Your submission has been received.',
            'id' => $submission->id
        ], 201);
    }
}