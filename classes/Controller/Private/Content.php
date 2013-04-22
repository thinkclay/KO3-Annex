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

    public function action_index()
    {
        Model_Annex_Content::overview();
    }

    public function action_list()
    {
        $model = Request::$current->param('model');
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        if ( $model AND $driver )
        {
            $model = 'Brass_'.ucfirst($model);

            // load all users from the database and list them here in a table
            $data = Brass::factory($model)->load(0)->as_array();

            $this->template->main->content = Theme::factory('views/content/list')->bind('data', $data);
        }
    }


    public function action_create()
    {
        $model = Request::$current->param('model');
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        if ( $model AND $driver )
        {
            // load all users from the database and list them here in a table
            $this->template->main->content = Theme::factory('views/forms/form')
                ->set('elements', Brass::factory('Brass_User')->as_form())
                ->set('method', 'POST');
        }
    }

    /**
     * Update Content
     *
     * If this is called dynamically with ajax and post vars, I kick in some CMS functionality
     * first i lookup the document to update it or I create a new document
     */
    public function action_update()
    {
        $model = Request::$current->param('model');
        $post = Request::$current->post();

        // $post['controller'] = 'site';
        // $post['action'] = 'index';
        // $post['ajax'] = true;
        // $post['path'] = 'cms_global.footer.copyright';
        // $post['data'] = 'something cool';

        if ( $post['ajax'] )
        {
            $this->auto_render = FALSE;
            $struct = [];

            Arr::from_char('.', $struct, $post['path'], $post['data']);

            // Set to an empty string for better display
            if ( $post['data'] == '<p><br></p>' )
            {
                $post['data'] = '';
            }

            // If this is a global content item
            if ( isset($struct['cms_global']) )
            {
                $post['global'] = 'true';
                $post['cms_global'] = $struct['cms_global'];
                unset($post['controller']);
                unset($post['action']);

                $params = ['global' => 'true'];
                $existing = BrassDB::instance()->find_one('brass_pages', $params);
            }
            // Must be local to a controller and action
            // in the future this may use ID's too
            else
            {
                $post['cms'] = $struct['cms'];

                $params = ['controller' => $post['controller'], 'action' => $post['action']];
                $existing = BrassDB::instance()->find_one('brass_pages', $params);
            }

            // If we found a document, lets update it
            if ( $existing )
            {
                $db = Brass::factory('brass_page', $params)->db();
                $updated = $db->update('brass_pages', $params, ['$set' => [$post['path'] => $post['data']]]);
            }
            // Otherwise we need to create it
            else
            {
                unset($post['path']);
                unset($post['ajax']);
                unset($post['data']);

                $doc = Brass::factory('brass_page');
                $doc->values($post);
                $created = $doc->create();
            }

            if ( isset($updated) OR isset($created) )
                echo json_encode(['status' => 'success']);
            else
                echo json_encode(['status' => 'failed']);
        }
    }
}