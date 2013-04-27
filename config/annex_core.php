<?php defined('SYSPATH') or die('No direct script access.');

return [

	// ideally will eventually support orm/jelly/mango/sprig, but for now just brass and mango
	'driver'	=> 'Brass',

	// Required Modules
	'requires'	=> [
		'less'	=> '1.0.0'
	]

];