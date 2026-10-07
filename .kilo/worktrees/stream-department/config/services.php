<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Provider Configuration
    |--------------------------------------------------------------------------
    | Choose which SMS provider to use: 'africas_talking' or 'bulksms'
    */
    'sms_provider' => env('SMS_PROVIDER', 'africas_talking'),

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

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

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', env('APP_URL') . '/auth/facebook/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Services
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS and WhatsApp notification services.
    | Choose one provider and configure the credentials.
    |
    */

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'), // WhatsApp enabled number
        'sms_from' => env('TWILIO_SMS_FROM'), // SMS enabled number
    ],

    'africas_talking' => [
        'username' => env('AFRICAS_TALKING_USERNAME'),
        'api_key' => env('AFRICAS_TALKING_API_KEY'),
        'shortcode' => env('AFRICAS_TALKING_SHORTCODE'), // SMS sender ID
        'sandbox' => env('AFRICAS_TALKING_SANDBOX', false),
    ],

    '360dialog' => [
        'api_key' => env('360DIALOG_API_KEY'),
        'phone_number_id' => env('360DIALOG_PHONE_NUMBER_ID'),
    ],

    'bulksms' => [
        'api_token' => env('BULKSMS_API_TOKEN'),
        'base_url' => env('BULKSMS_BASE_URL', 'https://bulksms.talksasa.com/api/v3'),
        'sender_id' => env('BULKSMS_SENDER_ID', 'VipersAcademy'),
    ],

];
