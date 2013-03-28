<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Private Default Controller
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
class Controller_Private_Annex extends Controller_Private
{
    public function before()
    {
        parent::before();

        $this->template->styles = [
            "http://twitter.github.com/bootstrap/assets/css/bootstrap.css" => "all",
            "http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" => "screen"
        ];
        $this->template->scripts = [];

        $this->template->head = '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
    }

    public function action_test()
    {
        $status = Annex::render('annex/modules/status');

        $this->template->main->content = View::factory('annex/index')
            ->bind('content', $status);
    }
}