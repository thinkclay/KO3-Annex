<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Account extends Controller_Public
{
    /**
     * Register Action
     *
     * @todo use a form generator built from the user model instead of hard coding fields
     */
    public function action_register()
    {
        if ( static::$user )
            $this->redirect('/account');

        $role = Request::$current->param('id');
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password_confirm');
        $email = $this->request->post('email');

        if ( $_POST )
        {
            $user_created = Model_Annex_Account::create($_POST, 'user');

            if ( $user_created )
                $this->redirect('/account');
        }

        $this->template->main->content = Theme::factory('views/forms/account/register')
            ->bind('username', $username)
            ->bind('password', $password)
            ->bind('password_confirm', $password_confirm)
            ->bind('email', $email);
    }

    /**
     * Login Action
     *
     * @todo Make the login checks more robust (aka: user doesnt exist)
     */
    public function action_login()
    {
        // If the user is already logged in, let's redirect them to the configured admin landing page
        if ( Authorize::instance()->logged_in() )
        {
            $this->redirect(Kohana::$config->load('annex_annex.admin.path'));
            return;
        }

        if ( $_POST )
        {
            $post = Validation::factory($_POST)
                ->rule('username', 'not_empty')
                ->rule('password', 'not_empty');

            if ( $post->check() )
            {
                $username = $this->request->post('username');
                $password = $this->request->post('password');
                $remember = $this->request->post('remember') ? $this->request->post('remember') : FALSE;
                $user = Authenticate::instance()->login($username, $password, $remember);

                if ( $user AND $user->role != 'pending' )
                {
                    // Redirect to account/index if login passed
                    $this->redirect(Kohana::$config->load('annex_annex.admin.path'));
                }
                else
                {
                    $message = 'Invalid username or password';
                    print_r($message);
                }
            }
            else
            {
                $message = 'Please enter a username and password';
                print_r($message);
            }
        }

        $this->template->class = 'three-one';

        $left = Theme::factory('views/forms/account/login')
            ->bind('username', $username)
            ->bind('password', $password);

        $right = Theme::factory('views/forms/account/register');

        $this->template->main->content = Theme::factory('views/container/2col')
            ->bind('left', $left)
            ->bind('right', $right);


    }
}