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
		
	public function before()
	{
	    parent::before();
		
	    static::$user = A2::instance()->get_user();
        
		if ($this->auto_render)
		{
			// Load our default wrappers to the view, but do it on before so that the controller->action can override
            $this->template->styles = [];
			$this->template->bind_global('user', self::$user);
			$this->template->header = Theme::view('default/views/container/header');
			$this->template->main = Theme::view('default/views/container/main');
			$this->template->footer = Theme::view('default/views/container/footer');
		}
	}
	
	public function after()
	{
		parent::after();
	}
}