<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Admin - wrapper for the admin pseudo namespace
 *
 * @package     Annex
 * @category    Admin
 * @author      Clay McIlrath
 */
abstract class Controller_Admin extends Controller_Template
{
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
        $this->template = Theme::factory()->get_theme_wrapper();

        parent::before();

        // load up the template
        $dir = strtolower(Request::$current->directory());
        $controller = strtolower(Request::$current->controller());
        $action = strtolower(Request::$current->action());

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
            $this->template->classes = [$dir, $controller, $action];

            if ( static::$user->role == 'admin' )
            {
                array_push($this->template->styles, Theme::style('admin.less'));
            }

            $this->template->bind_global('user', static::$user);
            $this->template->id = $controller;
            $this->template->header = Theme::factory('views/container/header');
            $this->template->main = Theme::factory('views/container/main');
            $this->template->footer = Theme::factory('views/container/footer');
        }
    }
}