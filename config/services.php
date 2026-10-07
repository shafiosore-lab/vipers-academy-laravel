<?php

/*
|--------------------------------------------------------------------------
| Third Party Services
|--------------------------------------------------------------------------
|
| The public website does not send email, SMS or WhatsApp messages: contact
| enquiries are written to the application log. Add credentials here only
| when a real delivery service is configured.
|
*/

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
