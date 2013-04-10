<?php defined('SYSPATH') or die('No direct script access.');

return [
    // Theme Settings
    'name'      => 'Ikaros',
    'overview'  => 'An alternative default theme',
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

    'settings' => [
        'media'     => 'builder',
        'etags'     => TRUE,
        'expires'   => '2 weeks',
    ],
    'compress'  => TRUE,
    'path'      => 'public/cache/', // relative path to a writable folder to store compiled / compressed css
    'styles'    => 'themes/ikaros/media/styles',
    'scripts'   => 'themes/ikaros/media/scripts',
    'images'    => 'themes/ikaros/media/images',
    'views'     => 'themes/ikaros/views/'
];