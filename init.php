<?php defined('SYSPATH') or die('No direct script access.');

define('ANXROOT', MODPATH.'annex'.DIRECTORY_SEPARATOR);
define('ANXMODS', ANXROOT.'submodules'.DIRECTORY_SEPARATOR);

// Annex Control Panel
Route::set('annex', 'annex(/<controller>(/<action>(/<model>(/<id>))))')
    ->defaults(array(
        'directory'     => 'private',
        'controller'    => 'annex',
        'action'        => 'index',
    ));

// Annex Login
Route::set('account login', 'account/login')
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'annex',
        'action'        => 'login',
    ));

Route::set('account logout', 'account/logout')
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'annex',
        'action'        => 'logout',
    ));

Route::set('account register', 'account/register')
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'annex',
        'action'        => 'register',
    ));

// Static file serving
Route::set('styles', 'styles/<file>', ['file' => '.+'])
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'styles',
        'action'        => 'index',
        'file'          => NULL,
    ));

Route::set('scripts', 'scripts(/<module>(/<file>))', ['file' => '.+'])
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'scripts',
        'action'        => 'index',
        'file'          => NULL,
    ));

Route::set('images', 'images(/<module>(/<file>))', ['file' => '.+'])
    ->defaults(array(
        'directory'     => 'public',
        'controller'    => 'images',
        'action'        => 'index',
        'file'          => NULL,
    ));


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 *
 * We want to extend and append to the bootstrap modules
 */
$annex_modules = [
    'authenticate'  => ANXMODS.'Authenticate',  // Custom authentication framework
    'brass'         => ANXMODS.'Brass', // ORM Layer for MongoDb
    'less'          => ANXMODS.'Less',  // Less Compiler Module
    'annex'         => MODPATH.'annex', // Annex Extension Loader
];

/**
 * Get our themes as modules
 *
 * Themes in the application path will override themes in modules path if they have the same name.
 * These themes will behave like modules calling the init file and gathering assets the same
 */
$theme_check = array_merge(glob(MODPATH.'*/*themes/*'), glob(APPPATH.'*themes/*'));
$themes = [];

foreach ( $theme_check as $theme )
{
    preg_match('/(?<=themes\/).*/i', $theme, $theme_name);
    $theme_name = strtolower($theme_name[0]);
    $themes[$theme_name] = $theme;
}

Annex::factory(Kohana::$_modules, $annex_modules, $themes);
