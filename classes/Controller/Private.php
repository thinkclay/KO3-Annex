<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Private - wrapper for the private pseudo namespace
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
abstract class Controller_Private extends Controller_Template
{
    public $template = NULL;

    public static $user = FALSE;

    /**
     * Before Other Functions
     *
     * We setup our global or wrapper functions for the private namespace (meaning anything behind a login)
     * we check for a user, make sure that user has permissions and then set some default styles, themes,
     * and views for the page
     */
    public function before()
    {
        // Set the default theme before we our Template Controller kicks in
        $this->template = 'views/wrapper';

        parent::before();

        static::$user = Authorize::instance()->get_user();

        // Check user auth and role: load up instance of A2 and pass it our config file
        $this->authorize = Authorize::instance();

        // load up the template
        $dir = Request::$current->directory();
        $controller = Request::$current->controller();
        $action = Request::$current->action();

        $this->template->classes = array($dir, $controller, $action);

        // First let's see if the user is logged in, and if not redirect to the proper error page
        if ( ! $this->authorize->logged_in() )
            $this->redirect('/account/login');

        // If this user is not allowed access to this controller or action lets show them that error page
        else if ( ! $this->authorize->allowed($controller, $action) )
            $this->redirect('error/denied');

        if ( $this->auto_render )
        {
            // Load our default wrappers to the view, but do it on before so that the controller->action can override
            $this->template->styles = [];
            $this->template->scripts = [];

            if ( static::$user->role == 'admin' )
            {
                array_push($this->template->styles, Theme::style('admin.less'));
            }

            $this->template->bind_global('user', self::$user);
            $this->template->id = $controller;
            $this->template->header = Theme::factory('views/container/header');
            $this->template->main = Theme::factory('views/container/main');
            $this->template->footer = Theme::factory('views/container/footer');
        }
    }
}