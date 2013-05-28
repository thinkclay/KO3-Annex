<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Admin_Users extends Controller_Admin
{

    /**
     * Index Action
     */
    public function action_index()
    {
        $this->template->main->content = Model_Annex_Content::show_list('user');
    }

    /**
     * Register Action
     *
     * @todo use a form generator built from the user model instead of hard coding fields
     */
    public function action_edit()
    {
        $role = Request::$current->param('id');
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password_confirm');
        $email = $this->request->post('email');

        if ( $post = $this->request->post() )
        {
            $this->auto_render = FALSE;

            if ( Model_Annex_Account::update($post, static::$user->_id) )
            {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Account updated'
                ]);
            }
            else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'We could not update your account with that information'
                ]);
            }

            return;
        }

        $this->template->main->content = Theme::factory('views/forms/account/manage')
            ->set('method', 'POST')
            ->bind('user', static::$user);
    }
}