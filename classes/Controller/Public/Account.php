<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Account Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Account extends Controller_Public
{
    /**
     * Register Action
     */
    public function action_register()
    {
        if ( static::$user AND ! static::$user->role == 'admin' )
            return $this->redirect('/account');

        $post = $this->request->post();

        if ( $post )
        {
            $user_created = Model_Annex_Account::create($post, 'pending', 'array');

            if ( $user_created )
            {
                $this->redirect('/account');
                return;
            }
        }

        $this->template->main->content = Theme::factory('views/forms/account/register')
            ->bind('username', $post['username'])
            ->bind('password', $post['password'])
            ->bind('password_confirm', $post['password_confirm'])
            ->bind('email', $post['email']);
    }

    /**
     * Login Action
     *
     * @todo Make the login checks more robust (aka: user doesnt exist)
     */
    public function action_login()
    {
        // If the user is already logged in, let's redirect them to the configured admin landing page
        if ( static::$user AND ! $this->authorize->allowed('admin') )
            return $this->redirect(Kohana::$config->load('annex_annex.admin.path'));

        if ( $_POST )
        {
            $post = Validation::factory($_POST)
                ->rule('username', 'not_empty')
                ->rule('password', 'not_empty');

            $this->auto_render = FALSE;
            $this->response->headers('Content-Type', 'application/json');

            if ( $post->check() )
            {


                $username = $this->request->post('username');
                $password = $this->request->post('password');
                $remember = $this->request->post('remember') ? $this->request->post('remember') : FALSE;
                $user = Authenticate::instance()->login($username, $password, $remember);

                if ( $user AND $user->role != 'pending' )
                {
                    // Redirect to account/index if login passed
                    echo json_encode([
                        'status'    => 'success',
                        'message'   => 'Successfully logged in. Please wait while you are redirected',
                        'redirect'  => Kohana::$config->load('annex_annex.admin.path')
                    ]);

                    return;
                }
                else
                {
                    echo json_encode([
                        'status'    => 'error',
                        'message'   => 'Invalid username or password'
                    ]);

                    return;
                }
            }
            else
            {
                echo json_encode([
                    'status'    => 'error',
                    'message'   => 'Please enter a username and password'
                ]);

                return;
            }
        }

        $left = Theme::factory('views/forms/account/login')
            ->bind('username', $username)
            ->bind('password', $password);

        $right = Theme::factory('views/forms/account/register');

        $this->template->main->content = Theme::factory('views/container/2col')
            ->bind('left', $left)
            ->bind('right', $right);
    }
}