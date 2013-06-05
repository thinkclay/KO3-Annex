<?php defined('SYSPATH') or die('No direct script access.');

return [
    // Module Information
    'module' => [
        'name'      => 'Accredit',
        'overview'  => 'Authentication and Authorization support for Annex',
        'version'   => '0.0.2',
        'url'       =>  [
            'author'    => 'http://thinkclay.com',
        ],

        // create a point release
        // levels: update, feature, security
        'changelog' => [
            '0.0.1' => ['update' => 'Initial Development of the Module'],
            '0.0.2' => ['update' => 'updated options to support newer mongo driver'],
        ],
    ]
];