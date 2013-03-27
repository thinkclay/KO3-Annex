Annex is starting out as a module for Kohana that helps unify projects and code. 
The module was created to solve problems with views, and media resources that required
deep coupling within the application, rather than staying self contained within the module
which is often more ideal.

# Features
With annex installed, it will do dependency checks to ensure that the modules and classes
needed for a project are loaded. It will also help the developer learn about upgrades, and
maintain a secure and current environment by checking for module versions from remote sources. 


# Configuration

The default config file is located in `MODPATH/annex/config/annex_core.php`.  
You should copy this file to `APPPATH/config/annex_core.php` and make changes there, in keeping with the [cascading filesystem](../kohana/files).

The configuration file contains an array of configuration groups. Which looks similar to:

	// Module Information
	'module'	=> array(
		'name'		=> 'Filedrop',
		'overview'	=> 'Fildrop HTML5 based file management',
		'version'	=> '0.0.1'
		'changelog'	=> array(
			'0.0.1'	=> array('update' => 'Initial Development of the Module')
		),
	),
	
	// Theme Settings
	'theme'		=> array(
		'styles'	=> 'resources/styles',
		'scripts'	=> 'resources/scripts'
	),

	// Resource Definition
	'resources' => array
	(
		'filedrop' 	=> null,
	),
	
	
	// ACL Rules
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
	
## Module Information
Name: A clean, one word name, case is not important, but multiple words should be separated with an underscore

Overview: Provide a description. Length is not terribly important, but try and make it concise

Version: Provide a 3 digit point release. The last point should be for minor updates that wont break existing installs, where second decimal can be used for more major realeases.

Changelog: This will be used in the feature to automate updates and make it easy to decide which updates are critical vs feature based
 