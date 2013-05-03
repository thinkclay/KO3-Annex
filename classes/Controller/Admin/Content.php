<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Private Default Controller
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
class Controller_Admin_Content extends Controller_Admin
{
    private $_model = FALSE;
    private $_id = FALSE;

    public function action_index()
    {
        $list = Model_Annex_Content::overview();

        $this->template->main->content = Theme::factory('views/content/model-list')->bind('data', $list);
    }

    public function action_list()
    {
        $model = Request::$current->param('model');
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        if ( $model AND $driver )
        {
            $brass_model = 'Brass_'.ucfirst($model);

            // load all users from the database and list them here in a table

            if ( ! class_exists('Model_'.$brass_model) )
            {
                $brass_model = 'Brass_'.ucfirst(preg_replace('/[s|es]$/i', '', $model));
            }

            $data = Brass::factory($brass_model)->load(0)->as_array();
            $view = Theme::factory("views/content/model-list-$model", NULL, TRUE);
            $model_name = preg_replace('/[es|s]$/i', '', $model);

            if ( $view )
            {
                $this->template->main->content = Theme::factory("views/content/model-list-$model")
                    ->bind('model', $model_name)
                    ->bind('data', $data);
            }
            else
            {
                $this->template->main->content = Theme::factory('views/content/model-list-default')
                    ->bind('model', $model_name)
                    ->bind('data', $data);
            }

        }
    }

    public function action_create()
    {
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));
        $model = 'Brass_'.ucfirst(Request::$current->param('model'));
        $post = $this->request->post();

        // If post data is set, we need to save
        // we also want some nice messages here so the user knows if worked or failed
        if ( $post )
        {
            $doc = Brass::factory($model);
            $post['owner'] = static::$user->_id;
            $post['created'] = time();
            $doc->values($post);

            if ( $doc->check() )
            {
                $doc->create();

                echo json_encode([
                    'status'    => 'success',
                    'message'   => 'Saved successfully'
                ]);
            }
            else
            {
                echo json_encode([
                    'status'    => 'error',
                    'message'   => 'Form failed to submit, you need to see fill out all the required fields'
                ]);
            }
        }
        // if there's no post data, we should show the model form
        else if ( $model AND $driver )
        {
            // load all users from the database and list them here in a table
            $this->template->main->content = Theme::factory('views/forms/form')
                ->set('elements', Brass::factory($model)->as_form())
                ->set('method', 'POST');
        }
    }

    public function action_view()
    {
        $model = 'Brass_'.ucfirst(Request::$current->param('model'));
        $id = Request::$current->param('id');
        $post = $this->request->post();

        // If post data is set, we need to save
        // we also want some nice messages here so the user knows if worked or failed
        if ( isset($model) AND isset($id) )
        {
            $doc = Brass::factory($model, ['_id' => new MongoId($id)])->load();

            if ( $doc )
            {
                $this->template->main->content = Theme::factory('views/forms/form')
                    ->set('class', 'ajax')
                    ->set('elements', $doc->as_form())
                    ->set('method', 'POST');
            }

            if ( $post )
            {
                $this->auto_render = FALSE;

                if ( isset($_FILES['photo']) )
                {
                    if ( $photo = Form::save_image($_FILES['photo']) )
                    {
                        $post['photo'] = [
                            'name'  => $photo,
                            'path'  => DOCROOT.'uploads/'.$photo
                        ];
                    }
                    else
                    {
                        $errors[] = 'There was a problem while uploading the image. Make sure it is uploaded and must be JPG/PNG/GIF file.';
                    }
                }

                $doc->values($post);

                if ( $doc->check() )
                {
                    $doc->update();

                    echo json_encode([
                        'status'    => 'success',
                        'message'   => 'Saved successfully'
                    ]);
                }
                else
                {
                    $errors[] = 'Form failed to submit, you need to see fill out all the required fields';

                    echo json_encode([
                        'status'    => 'error',
                        'message'   => $errors
                    ]);
                }
            }
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
                $updated = BrassDB::instance()->update('brass_pages', $params, ['$set' => [$post['path'] => $post['data']]]);
            }
            // Otherwise we need to create it
            else
            {
                unset($post['path']);
                unset($post['ajax']);
                unset($post['data']);

                $created = BrassDB::instance()->insert('brass_pages', $post);
            }

            if ( isset($updated) OR isset($created) )
                echo json_encode(['status' => 'success']);
            else
                echo json_encode(['status' => 'failed']);
        }
    }
}