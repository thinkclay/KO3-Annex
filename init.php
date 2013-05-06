<?php defined('SYSPATH') or die('No direct script access.');

define('ANXROOT', MODPATH.'annex'.DIRECTORY_SEPARATOR);
define('ANXMODS', ANXROOT.'submodules'.DIRECTORY_SEPARATOR);

// Annex Control Panel
Route::set('admin', 'admin')
    ->defaults([
        'directory'     => 'admin',
        'controller'    => 'admin',
    ]);

Route::set('admin become user', 'admin/become-user(/<id>)')
    ->defaults([
        'directory'     => 'admin',
        'controller'    => 'admin',
        'action'        => 'become_user'
    ]);

Route::set('admin content', 'admin/content(/<action>(/<model>(/<id>)))')
    ->defaults([
        'directory'     => 'admin',
        'controller'    => 'content'
    ]);

// Annex Account Stuff
Route::set('account', 'account')
    ->defaults([
        'directory'     => 'private',
        'controller'    => 'account',
    ]);

Route::set('account login', 'account/login')
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'account',
        'action'        => 'login',
    ]);

Route::set('account register', 'account/register')
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'account',
        'action'        => 'register',
    ]);

Route::set('account logout', 'account/logout')
    ->defaults([
        'directory'     => 'private',
        'controller'    => 'account',
        'action'        => 'logout',
    ]);

Route::set('account manage', 'account/manage')
    ->defaults([
        'directory'     => 'private',
        'controller'    => 'account',
        'action'        => 'manage',
    ]);

// Static file serving
Route::set('styles', 'styles(/<module>(/<file>))', ['file' => '.+'])
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'media',
        'action'        => 'index',
        'type'          => 'styles',
        'file'          => NULL,
    ]);

Route::set('scripts', 'scripts(/<module>(/<file>))', ['file' => '.+'])
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'media',
        'action'        => 'index',
        'type'          => 'scripts',
        'file'          => NULL,
    ]);

Route::set('images', 'images(/<module>(/<file>))', ['file' => '.+'])
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'media',
        'action'        => 'index',
        'type'          => 'images',
        'file'          => NULL,
    ]);


Route::set('annex_error', 'error/<action>(/<message>)', ['action' => '[0-9]++', 'message' => '.+'])
    ->defaults([
        'directory'     => 'public',
        'controller'    => 'error'
    ]);


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 *
 * We want to extend and append to the bootstrap modules
 */
$annex_modules = [
    'accredit'      => ANXMODS.'accredit',  // Custom authentication framework
    'brass'         => ANXMODS.'brass',     // ORM Layer for MongoDb
    'less'          => ANXMODS.'less',      // Less Compiler Module
    'annex'         => MODPATH.'annex',     // Annex Extension Loader
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
