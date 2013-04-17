<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Public - wrapper for the public pseudo namespace
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
abstract class Controller_Public extends Controller_Template
{
    public $template = NULL;

    public static $user = FALSE;

    /**
     * Before Other Functions
     *
     * We setup our global or wrapper functions for the public namespace (meaning anything not behind a login)
     * we still check for a user so that any account / user info that may be useful on the page is passed
     * to views globally, and then we set some default view wrappers
     */
    public function before()
    {
        // Set the default theme before we our Template Controller kicks in
        $this->template = Theme::factory()->get_theme_wrapper();

        parent::before();

        static::$user = Authorize::instance()->get_user();

        $dir = strtolower(Request::$current->directory());
        $controller = strtolower(Request::$current->controller());
        $action = strtolower(Request::$current->action());

        if ( $this->auto_render )
        {
            // Load our default wrappers to the view, but do it on before so that the controller->action can override
            $this->template->styles = [];
            $this->template->scripts = [];
            $this->template->js_vars = ['controller' => $controller, 'action' => $action];

            $this->template->bind_global('user', self::$user);
            $this->template->header = Theme::factory('views/container/header');
            $this->template->main = Theme::factory('views/container/main');
            $this->template->footer = Theme::factory('views/container/footer');
        }
    }

    public function after()
    {
        if ( static::$user AND static::$user->role == 'admin' )
        {
            $this->template->styles[Theme::style('admin.less')] = 'all';
            $this->template->scripts[] = '/scripts/default/cms.js';
        }

        parent::after();
    }
}