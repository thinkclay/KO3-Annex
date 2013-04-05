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

    public function action_list()
    {
        $model = Request::$current->param('model');
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        if ( $model AND $driver )
        {
            // load all users from the database and list them here in a table
            var_dump( Brass::factory('Brass_User')->load()->as_array() );
        }
    }


    public function action_create()
    {
        $model = Request::$current->param('model');
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        if ( $model AND $driver )
        {
            // load all users from the database and list them here in a table
            $form_data = Brass::factory('Brass_User')->as_form();
            $this->template->main->content = Theme::factory('views/forms/form')
                ->bind('elements', $form_data);
        }
    }
}