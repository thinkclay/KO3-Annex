<?php defined('SYSPATH') or die('No direct script access.');

return [
    // Theme Settings
    'name'      => 'Default',
    'overview'  => 'Annex Core Theme',
    'version'   => '0.1.0',
    'url'       =>  [
        'author'    => 'http://thinkclay.com',
    ],
    // create a point release
    // levels: update, feature, security
    'changelog' => [
        '0.0.1' => ['update' => 'Initial Development of the Module'],
        '0.0.2' => ['update' => 'authenticate, acl, and admin panel']
    ],

    'settings'  => [
        'media' => 'builder'
    ],
    'compress'  => TRUE,
    'cache'      => 'cache/', // relative path from DOCROOT with a writable folder to store compiled / compressed css
    'styles'    => 'themes/default/media/styles',
    'scripts'   => 'themes/default/media/scripts',
    'views'     => 'themes/default/views/'
];