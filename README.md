# Annex: A full blown CMS for Kohana 3.3

Annex is starting out as a module for Kohana that helps unify projects and code.
The module was created to solve problems with views, and media resources that required
deep coupling within the application, rather than staying self contained within the module
which is often more ideal. The module takes inspiration from rails_admin, with it's autogeneration of form
fields based on model definition and ease of installation.

# Features
With annex installed, it will do dependency checks to ensure that the modules and classes
needed for a project are loaded. It will also help the developer learn about upgrades, and
maintain a secure and current environment by checking for module versions from remote sources.


# Configuration

The default config file is located in `MODPATH/annex/config/annex_core.php`.
You should copy this file to `APPPATH/config/annex_core.php` and make changes there, in keeping with the [cascading filesystem](../kohana/files).

The configuration file contains an array of configuration groups. Which looks similar to:

```php
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
```

## Module Information
Name: A clean, one word name, case is not important, but multiple words should be separated with an underscore

Overview: Provide a description. Length is not terribly important, but try and make it concise

Version: Provide a 3 digit point release. The last point should be for minor updates that wont break existing installs, where second decimal can be used for more major realeases.

Changelog: This will be used in the feature to automate updates and make it easy to decide which updates are critical vs feature based

## Validating a User (registration)
```php
// create the account
$user = Brass::factory('Brass_User');
$user->created = time();
$user->role = $role;
$user->values($post_validation->as_array());

try
{
    if ( $user->check() )
    {
        if ( $user->create() )
        {
            Authenticate::instance()->complete_login($user, TRUE);

            if ( $response == 'array' )
                return [
                    'status' => 'success',
                    'message' => 'user created successfully'
                ];
            else
                return TRUE;
        }
    }
}
catch (Brass_Validation_Exception $e)
{
    if ( $response == 'array' )
        return [
            'status' => 'error',
            'message' => 'user creation failed due to user errors',
            'errors' => $e->array->errors()
        ];
    else
        return FALSE;
}
```

## Sending System Emails
Annex has a nice email class for sending out theme-based and editable emails. The factory is your construct taking three parameters:
  1. A string with the "key" name or identifier - This key is first used to check the database for a custom email template, and if none is found,
  the key is exploded on dotnotation and searches for a view file in the resulting folder (ie: YOURTHEME/mail/notice/investors/.withdrawal)
  2. The second string should be a list of emails (as the php mail function would recieve them) or a key identifying your emails as used in your email config file
  3. An array can be passed to be used in your template as mustache variables (ie: {{username}}, {{amount}}, {{email}})

```php
Model_Annex_Email::factory()->send(
  'mail.notice.investors.withdrawal',
  'cmcilrath@somedomain.com',
  [
    'username'  => static::$user->username,
    'amount'    => $post['amount'],
    'email'     => static::$user->email
  ]
);
```


----
This module is released under an [MIT opensource license](http://opensource.org/licenses/MIT)