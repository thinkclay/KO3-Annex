<?php defined('SYSPATH') or die('No direct script access.');

return [

	// ideally will eventually support orm/jelly/mango/sprig, but for now just mango
	'driver'	=> 'mango',

	// Required Modules
	'requires'	=> [
		'less'	=> '1.0.0'
	]

];