<?php

namespace App\Http\Controllers;

use App\Mail\FormSubmissionNotification;
use App\Models\ContactForm;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactFormController extends Controller
{
    public function show($slug)
    {
        $form = ContactForm::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        return view('contact-form', compact('form'));
    }

    public function submit(Request $request, $slug)
    {
        $form = ContactForm::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $rules = [];
        foreach ($form->fields as $field) {
            $fieldRules = [];
            
            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            }
            
            if ($field['type'] === 'email') {
                $fieldRules[] = 'email';
            }
            
            if (!empty($fieldRules)) {
                $rules[$field['name']] = $fieldRules;
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [];
        foreach ($form->fields as $field) {
            $value = $request->input($field['name']);
            if ($value !== null) {
                $data[$field['name']] = $value;
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
                ->send(new FormSubmissionNotification($submission));
        } catch (\Exception $e) {
            \Log::error('Failed to send form notification: ' . $e->getMessage());
        }

        return back()->with('success', $form->success_message);
    }
}