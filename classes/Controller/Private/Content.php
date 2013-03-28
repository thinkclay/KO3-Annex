<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Private Default Controller
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
class Controller_Private_Content extends Controller_Private
{
    private $_model = FALSE;
    private $_id = FALSE;

    public function before()
    {
        parent::before();

        $this->_model = 'Model_Content_'.ucfirst(Request::$current->param('model'));
        $this->_id = Request::$current->param('id');

        $this->template->styles = [
            "http://twitter.github.com/bootstrap/assets/css/bootstrap.css" => "all",
            "http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" => "screen"
        ];
        $this->template->scripts = [];

        $this->template->head = '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
    }

    public function action_index()
    {
        Model_Content::overview();
    }

    public function action_create()
    {
        if ( $model = $this->_model )
        {
            $model::create();
        }
        else
        {
            echo 'put some kind of quick select here for picking a content type to create';
        }
    }
}