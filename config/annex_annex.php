<?php defined('SYSPATH') OR die('No direct script access.');

return [
    // Module Information
    'module' => [
        'name'      => 'Annex',
        'overview'  => 'Annex Core Framework',
        'version'   => '0.1.2',
        'url'       =>  [
            'author'    => 'http://thinkclay.com',
        ],

        // create a point release
        // levels: update, feature, security, patch
        'changelog' => [
            '0.1.2' => ['patch'     => 'Clean up of unit tests, and moved some account logic around for better error reporting'],
            '0.1.1' => ['patch'     => 'Documentation, formatting, and Brass Submodule'],
            '0.1.0' => ['update'    => 'Unit tests'],
            '0.0.5' => ['patch'     => 'Bug fixes and initial unit tests'],
            '0.0.4' => ['patch'     => 'Updated event submodule'],
            '0.0.3' => ['patch'     => 'Better authentication, most submodules decoupled'],
            '0.0.2' => ['patch'     => 'Authenticate, acl, and admin panel'],
            '0.0.1' => ['update'    => 'Initial Development of the Module'],
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