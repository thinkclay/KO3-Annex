<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Private_Account extends Controller_Private
{

    /**
     * Index Action
     */
    public function action_index()
    {
        $this->redirect('/'.static::$user->role);
    }

    /**
     * Register Action
     *
     * @todo use a form generator built from the user model instead of hard coding fields
     */
    public function action_manage()
    {
        $role = Request::$current->param('id');
        $username = strtolower($this->request->post('username'));
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password_confirm');
        $email = strtolower($this->request->post('email'));

        if ( $post = $this->request->post() )
        {
            if ( isset($post['username']) )
                $post['username'] = strtolower($post['username']);

            if ( isset($post['email']) )
                $post['email'] = strtolower($post['email']);

            $this->auto_render = FALSE;

            //todo - find a better place for this, and/or implement general business
            //logic rules for automatically moving through roles
            $u = static::$user;
            $roleChanged = false;
            if (method_exists($u, 'checkRole')) {
                $roleChanged = $u->checkRole($post);
            }

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

    /**
     * Logout Action
     *
     * @todo if we end up implementing local storage sync, this would be a good spot to purge that cache
     */
    public function action_logout()
    {
        Authenticate::instance()->logout(TRUE);
        $this->redirect();
    }
}