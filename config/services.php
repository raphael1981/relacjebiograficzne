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
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'domains' => [
        'admin' => 'adminrelacje.spaceforweb.pl',
        'customers' => 'relacje.spaceforweb.pl'
    ],
    'trade' => [
        'it',
        'ochrona zdrowia'
    ],
    'stbeforever' => [
        [
            'number'=>-1,
            'name'=>'Nie potwierdzony'
        ],
        [
            'number'=>0,
            'name'=>'Potwierdzony'
        ]
    ],
    'stafterver' => [
        [
            'number'=>1,
            'name'=>'Zakceptowany'
        ]
    ],
    'adminemail'=>'relacje@dsh.usermd.net', //m.buko@dsh.waw.pl //relacje@dsh.waw.pl

    'recaptcha'=>[
        'site_key'=>'6LcG7Q8UAAAAABBd7dz3TWmfdgRKkwbZgMEzYMVO',
        'secret_key'=>'6LcG7Q8UAAAAAJt7_JoL7j3rcuGYx_j0x89xAgon'
    ],
    'protocol'=>[
        'admin'=>'http',
        'customer'=>'https'
    ],
    'polimorfic'=>[
        'interviewees',
        'tags',
        'periods',
        'galleries',
        'records',
        'redactors'
    ],
    'timesign_xmlpath'=>'home/timemarker.dsh.waw.pl/public/xml'


];
