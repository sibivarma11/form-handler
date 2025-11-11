<?php

namespace SibiVarma\FormSubmissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SibiVarma\FormSubmissions\Models\FormSubmission;
use SibiVarma\FormSubmissions\Mail\FormSubmissionNotification;

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
            Mail::to(config('form-submissions.notification_email', config('mail.from.address')))
                ->send(new FormSubmissionNotification($submission));
        } catch (\Exception $e) {
            \Log::error('Failed to send form notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => config('form-submissions.success_message', 'Thank you! Your submission has been received.'),
            'id' => $submission->id
        ], 201);
    }
}