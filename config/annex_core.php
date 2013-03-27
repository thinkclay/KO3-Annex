<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	// ideally will eventually support orm/jelly/mango/sprig, but for now just mango
	'driver'	=> 'mango', 
	
	// Required Modules
	'requires'	=> array(
		'less'	=> '1.0.0'
	)
	
);