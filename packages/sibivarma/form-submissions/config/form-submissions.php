<?php

return [
    'notification_email' => env('FORM_SUBMISSIONS_EMAIL', env('MAIL_FROM_ADDRESS')),
    'success_message' => 'Thank you! Your submission has been received.',
];