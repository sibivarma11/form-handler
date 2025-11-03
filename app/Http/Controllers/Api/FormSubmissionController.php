<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionNotification;
use App\Models\ContactForm;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    public function submit(Request $request, $slug)
    {

        
        $form = ContactForm::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$form) {
            return response()->json(['error' => 'Form not found'], 404);
        }

        // Accept all submitted fields dynamically
        $data = $request->all();
        
        // Basic validation for email field if present
        if (isset($data['email'])) {
            $validator = Validator::make($data, ['email' => 'email']);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        $submission = FormSubmission::create([
            'contact_form_id' => $form->id,
            'data' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            Mail::to($form->notification_email)
                ->queue(new FormSubmissionNotification($submission));
        } catch (\Exception $e) {
            \Log::error('Failed to queue form notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => $form->success_message,
            'submission_id' => $submission->id
        ], 201);
    }
}