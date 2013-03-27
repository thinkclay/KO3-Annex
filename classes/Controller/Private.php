<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Private - wrapper for the private pseudo namespace
 *
 * @package		Annex
 * @category	Private
 * @author		Clay McIlrath
 */
abstract class Controller_Private extends Controller_Template
{
	public $template = '../themes/default/views/wrapper';

	public static $user = false;

	public function before()
	{
		parent::before();

		static::$user = A2::instance('private')->get_user();

		// Check user auth and role: load up instance of A2 and pass it our config file
        $this->a2 = A2::instance('private');
        // $this->a1 = $this->a2->a1;

		// load up the template
		$dir = Request::$current->directory();
		$controller = Request::$current->controller();
		$action = Request::$current->action();

		$this->template->classes = array($dir, $controller, $action);

		// First let's see if the user is logged in, and if not redirect to the proper error page
		if ( ! $this->a2->logged_in() )
			$this->redirect('/login');

		// If this user is not allowed access to this controller or action lets show them that error page
		else if ( ! $this->a2->allowed($controller, $action) )
			$this->redirect('error/denied');

		if ( $this->auto_render )
		{
			// Load our default wrappers to the view, but do it on before so that the controller->action can override
			$this->template->bind_global('user', self::$user);
			$this->template->id = $controller;
			$this->template->header = View::factory('blocks/container/header');
			$this->template->header->content = View::factory('blocks/navigation/private/header');
			$this->template->header->after = View::factory('blocks/navigation/private/primary');
			$this->template->main = View::factory('blocks/container/main');
			$this->template->footer = View::factory('blocks/qbar/dashboard');
		}
	}

	public function after()
	{
		parent::after();
		Event::instance()->fire(Event::assemble());
	}
}