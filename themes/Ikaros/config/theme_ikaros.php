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
        'name'      => 'default',
        'settings'  => [
            'media' => 'builder'
        ],
        'compress'  => TRUE,
        'path'      => 'public/cache/', // relative path to a writable folder to store compiled / compressed css
        'styles'    => 'themes/default/media/styles',
        'scripts'   => 'themes/default/media/scripts',
        'views'     => 'themes/default/views/'
    ],


    /**
     * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
     * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
     */
    'resources' => [
        'annex'     => NULL,
    ],

    /*
     * The ACL Rules (Again, string IDs are fine, use of ACL_Role/Resource_Interface objects also possible)
     * Split in allow rules and deny rules, one sub-array per rule
     */
    'rules' => [
        'allow' => [
            'annex' => [
                'role'      => ['admin'],
                'resource'  => ['annex'],
                'privilege' => ['index']
            ]
        ],
        'deny' => []
    ]
];