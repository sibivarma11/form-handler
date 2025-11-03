<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .field { margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px; }
        .field-label { font-weight: bold; color: #4F46E5; margin-bottom: 5px; }
        .field-value { color: #1f2937; }
        .footer { text-align: center; padding: 15px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Form Submission</h2>
            <p>{{ $submission->contactForm->name }}</p>
        </div>
        
        <div class="content">
            <p><strong>Submitted:</strong> {{ $submission->created_at->format('F j, Y g:i A') }}</p>
            
            @foreach($submission->data as $key => $value)
                <div class="field">
                    <div class="field-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                    <div class="field-value">
                        @if(is_array($value))
                            {{ implode(', ', $value) }}
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="footer">
            <p>IP Address: {{ $submission->ip_address }}</p>
            <p>This is an automated message from your contact form system.</p>
        </div>
    </div>
</body>
</html>