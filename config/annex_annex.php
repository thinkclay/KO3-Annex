<?php defined('SYSPATH') or die('No direct script access.');

return [
    // Module Information
    'module' => [
        'name'      => 'Annex',
        'overview'  => 'Annex Core Framework',
        'version'   => '0.0.2',
        'url'       =>  [
            'author'    => 'http://thinkclay.com',
        ],

        // create a point release
        // levels: update, feature, security
        'changelog' => [
            '0.0.1' => ['update' => 'Initial Development of the Module'],
            '0.0.2' => ['update' => 'authenticate, acl, and admin panel']
        ],
    ],

    'admin' => [
        'path' => '/annex'
    ],

    // Required Modules
    'requires' => [],

    // Optional Module Support
    'optional' => [],

    // Themeing Settings
    'theme' => [
        'public'    => 'default',
        'private'   => 'default',
        'admin'     => 'default',
        'settings'  => [
            'media' => 'builder'
        ],
        'public'    => TRUE, // Should this be used for the public site?
        'private'   => TRUE, // Should this be used for the public site?
        'compress'  => TRUE,
        'path'      => 'public/cache/', // relative path to a writable folder to store compiled / compressed css
        'styles'    => 'themes/default/media/styles',
        'scripts'   => 'themes/default/media/scripts',
        'views'     => 'themes/default/views/'
    ]
];