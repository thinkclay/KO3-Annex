<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'driver'     => 'Brass',
	'user_model' => 'Brass_User',
	'cost'       => 12,    // Bcrypt Cost - any number between 4 and 31 -> higher = stronger hash
	'cookie'     => array(
		'key'         => 'authenticate_{name}_autologin',
		'lifetime'    => 1209600, // two weeks
	),
	'columns'   => array(
		'username'    => 'username',
		'password'    => 'password',
		'token'       => 'token',
		'last_login'  => 'last_login', // (optional)
		'login_count' => 'login_count' // (optional)
	),
	'session'  => array(
		'type'        => 'native' // native or database
	)
);
