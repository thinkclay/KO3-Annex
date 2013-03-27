<?php defined('SYSPATH') or die('No direct script access.');

define('ANXROOT', MODPATH.'annex'.DIRECTORY_SEPARATOR);
define('ANXMODS', ANXROOT.'submodules'.DIRECTORY_SEPARATOR);

// Annex Control Panel
Route::set('annex', 'annex(/<action>(/<id>))')
	->defaults(array(
		'directory'		=> 'private',
		'controller'	=> 'annex',
		'action'		=> 'test',
	));

// Annex Login
Route::set('login', 'login(/<action>(/<id>))')
	->defaults(array(
		'directory'		=> 'public',
		'controller'	=> 'annex',
		'action'		=> 'login',
	));

// Static file serving
Route::set('styles', 'styles(/<module>(/<file>))', array('file' => '.+'))
	->defaults(array(
		'directory'		=> 'public',
		'controller'	=> 'annex',
		'action'		=> 'styles',
		'file'			=> NULL,
	));

Route::set('scripts', 'scripts(/<module>(/<file>))', array('file' => '.+'))
	->defaults(array(
		'directory'		=> 'public',
		'controller'	=> 'annex',
		'action'		=> 'scripts',
		'file'			=> NULL,
	));


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 *
 * We want to extend and append to the bootstrap modules
 */
$annex_modules = array(
	'a1'		=> ANXMODS.'auth/a1',       // Wouters a1 module for user authentication
	'acl'		=> ANXMODS.'auth/acl',      // Wouters acl module for user authorization
    'a2'		=> ANXMODS.'auth/a2',       // Wouters a2 module to tie a1 and acl together
	'less'		=> ANXMODS.'less',			// Less Compiler Module
	'annex'		=> MODPATH.'annex',			// Annex Extension Loader
);

Annex::factory(Kohana::$_modules, $annex_modules);
