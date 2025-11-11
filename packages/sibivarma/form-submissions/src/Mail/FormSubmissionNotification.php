<?php

namespace SibiVarma\FormSubmissions\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SibiVarma\FormSubmissions\Models\FormSubmission;

class FormSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FormSubmission $submission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Form Submission',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'form-submissions::emails.form-submission',
        );
    }
}