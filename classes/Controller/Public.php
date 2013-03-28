<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Public - wrapper for the public pseudo namespace
 *
 * @package		Annex
 * @category	Public
 * @author		Clay McIlrath
 */
abstract class Controller_Public extends Controller_Template
{
	public $template = '../themes/default/views/wrapper';

	public static $user = false;

	/**
     * Before Other Functions
     *
     * We setup our global or wrapper functions for the public namespace (meaning anything not behind a login)
     * we still check for a user so that any account / user info that may be useful on the page is passed
     * to views globally, and then we set some default view wrappers
     */
	public function before()
	{
	    parent::before();

	    static::$user = Authorize::instance()->get_user();

		if ($this->auto_render)
		{
			// Load our default wrappers to the view, but do it on before so that the controller->action can override
            $this->template->styles = [];
			$this->template->bind_global('user', self::$user);
			$this->template->header = Theme::view('views/container/header');
			$this->template->main = Theme::view('views/container/main');
			$this->template->footer = Theme::view('views/container/footer');
		}
	}
}