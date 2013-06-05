<?php defined('SYSPATH') or die('No direct access allowed.');

return [
    'driver'     => 'Brass',
    'user_model' => 'Brass_User',
    'cost'       => 12,    // Bcrypt Cost - any number between 4 and 31 -> higher = stronger hash
    'cookie'     => [
        'key'         => 'authenticate_{name}_autologin',
        'lifetime'    => 1209600, // two weeks
    ],
    'columns'   => [
        'username'    => 'username',
        'email'       => 'email',
        'password'    => 'password',
        'token'       => 'token',
        'last_login'  => 'last_login', // (optional)
        'login_count' => 'login_count' // (optional)
    ],
    'session'       => [
        'type'        => 'native' // native or database
    ]
];