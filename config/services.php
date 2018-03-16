<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model'  => App\Models\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'twitter' => [
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('SOCIALITE_TWITTER_CALLBACK'),
        'access_token'  => env('TWITTER_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_ACCESS_SECRET'),
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('SOCIALITE_GITHUB_CALLBACK'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_APP_ID'),
        'app_id'        => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect'      => env('SOCIALITE_FACEBOOK_CALLBACK'),
    ],

    'google' => [
        'recaptcha_site_key'   => env('GOOGLE_RECAPTCHA_SITE_KEY'),
        'recaptcha_secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
        'client_id'            => env('GOOGLE_CLIENT_ID'),
        'client_secret'        => env('GOOGLE_CLIENT_SECRET'),
        'redirect'             => env('SOCIALITE_GOOGLE_CALLBACK'),
        // 'social_auto_publish_client_id'      => env('GOOGLE_SOCIAL_AUTO_PUBLISH_CLIENT_ID'),
        // 'social_auto_publish_client_secret'  => env('GOOGLE_SOCIAL_AUTO_PUBLISH_CLIENT_SECRET'),
        // 'social_auto_publish_client_api_key' => env('GOOGLE_SOCIAL_AUTO_PUBLISH_API_KEY'),
        // 'social_auto_publish_redirect'       => env('GOOGLE_SOCIAL_AUTO_PUBLISH_CALLBACK'),
    ]

];
