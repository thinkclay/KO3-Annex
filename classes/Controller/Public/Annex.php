<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Annex extends Controller_Public
{
    public function before()
    {
        parent::before();

        $this->template->styles = [
            "http://twitter.github.com/bootstrap/assets/css/bootstrap.css" => "all",
            "http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" => "screen"
        ];
        $this->template->scripts = [
        ];

        $this->template->head = '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
    }

    public function action_register()
    {
        $role = Request::$current->param('id');
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password_confirm');
        $email = $this->request->post('email');

        if ( $_POST )
        {
            $check = Model_User::create($_POST, 'user');

            if ( is_array($check) )
            {
                $check['success'] = 0;
                echo json_encode($check);
            }
            else if ( $check == false )
            {
                echo json_encode([
                    'success'   => 0,
                    'message'   =>'User creation failed'
                ]);
            }
            else if ( $check == true )
            {
                echo json_encode([
                    'success'   => 1,
                    'message'   => 'An email has been sent to '.$_POST['email'].'<br /> Follow instructions to complete registration.'
                ]);
            }
        }

        $this->template->main->content = Theme::view('default/views/forms/register')
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
        if ( Authorize::instance('private')->logged_in() )
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

        $this->template->main->content = Theme::view('default/views/forms/login')
            ->bind('username', $username)
            ->bind('password', $password);
    }

    /**
     * Function for user logout
     *
     * @return  void
     */
    public function action_logout()
    {
        Authenticate::instance()->logout(TRUE);
        $this->redirect();
    }
}