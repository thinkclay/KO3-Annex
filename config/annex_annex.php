<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	// Module Information
	'module'	=> array(
		'name'		=> 'Annex',
		'overview'	=> 'Annex Core Framework',
		'version'	=> '0.0.1',
		'url'		=>	array(
			'author'	=> 'http://arr.ae', 
		),
		
		// create a point release
		// levels: update, feature, security
		'changelog'	=> array(
			'0.0.1'	=> array('update' => 'Initial Development of the Module')
		),
	),
	
	// Required Modules
	'requires'	=> array(
		'less'	=> '1.0.0'
	),
	
	// Optional Module Support
	'optional'	=> array(
	
	),
	
	// Themeing Settings
	'theme'		=> array(
		'settings'	=> array(
			'media'	=> 'builder'
		),
		'compress'	=> true,
		'path'		=> 'public/cache/', // relative path to a writable folder to store compiled / compressed css
		'styles'	=> 'themes/default/media/styles',
		'scripts'	=> 'themes/default/media/scripts',
		'views'		=> 'themes/default/views/'
	),

	/*
	 * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
	 * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
	 */
	'resources' => array
	(
		// ADD YOUR OWN RESOURCES HERE
		'filedrop' 	=> null,
	),

	
	/**
	 * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
	 * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
	 */
	'resources' => array
	(
		'annex'		=> null,
	),
	
	/*
	 * The ACL Rules (Again, string IDs are fine, use of ACL_Role/Resource_Interface objects also possible)
	 * Split in allow rules and deny rules, one sub-array per rule
	 */
	'rules' => array
	(
		'allow' => array
		(
			'annex' => array(
				'role'		=> array('admin'),
				'resource'	=> array('annex'),
				'privilege'	=> array('index'),
			)
		),
		'deny' => array
		(
			// ADD YOUR OWN DENY RULES HERE
		)
	)
);