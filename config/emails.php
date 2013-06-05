<?php defined('SYSPATH') or die('No direct script access.');

return [
    'admins' => ['you@yourdomain.com, someoneelse@yourdomain.com'],

    // The system email to send FROM
    'system' => 'robot@yourdomain.com',

    // Identify our email templates
    'templates' => [
        'mail.exception.generic' => [
            'name'          => 'An Exception was thrown',
            'subject'       => 'An Exception was thrown',
            'description'   => 'This email gets sent out whenever an exception is thrown'
        ],
        'mail.exception.severe' => [
            'name'          => 'An Exception was thrown that is of status: severe',
            'subject'       => 'An sever error occurred',
            'description'   => 'This email gets sent out whenever a severe exception is thrown'
        ],
    ]
];