<?php (defined('SYSPATH')) OR die('No direct script access.');

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
        if (static::$user AND ! static::$user->role == 'admin')
            return $this->redirect('/account');

        if ($post = $this->request->post())
        {
            if (isset($post['username']))
            {
                $post['username'] = strtolower($post['username']);
            }

            if (isset($post['email']))
            {
                $post['email'] = strtolower($post['email']);
            }

            $user_created = Model_Annex_Account::create($post, 'pending', 'array');

            if ($user_created)
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
        if (static::$user AND ! $this->authorize->allowed('admin'))
            return $this->redirect('/account');

        if ($_POST)
        {
            $post = Validation::factory($_POST)
                ->rule('username', 'not_empty')
                ->rule('password', 'not_empty');

            $this->auto_render = FALSE;
            $this->response->headers('Content-Type', 'application/json');

            if ($post->check())
            {
                $username = strtolower($this->request->post('username'));
                $password = $this->request->post('password');
                $remember = $this->request->post('remember') ? $this->request->post('remember') : FALSE;
                $user = Authenticate::instance()->login($username, $password, $remember);

                if ($user)
                {
                    // Redirect to account/index if login passed
                    echo json_encode([
                        'status'    => 'success',
                        'message'   => 'Successfully logged in. Please wait while you are redirected',
                        'redirect'  => '/account'
                    ]);

                    Event::instance()->fire('ACCOUNT_LOGIN', $user);

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

        $this->template->main->before = Theme::factory('views/container/hero');
        $this->template->main->content = Theme::factory('views/container/2col')
            ->bind('left', $left)
            ->bind('right', $right);
    }

    public function action_forgot()
    {
        $this->template->main->before = Theme::factory('views/container/hero');
        $this->template->main->content = '';

        if ($this->request->param('token'))
        {
            $data = explode('-', $this->request->param('token'));

            // If the token is expired, show an error and move on
            if ($data[2] < time())
            {
                $this->template->main->content .= '<div class="status-box warning">Your token has expired, please reset again</div>';
            }
            elseif ($user = Model_Annex_Account::find_user(strtolower($data[0])))
            {
                Authenticate::instance()->complete_login($user, $remember = TRUE);
                $this->redirect('/account/manage');
            }
        }
        elseif (isset($_REQUEST['username']))
        {
            $_REQUEST['username'] = strtolower($_REQUEST['username']);

            if ($user = Model_Annex_Account::find_user($_REQUEST['username']))
            {
                $token = Model_Annex_Account::generate_token($user, strtotime('+1 hour'));
                $url = URL::base('http').'account/reset/'.$token;
                $data = array_merge($user->as_array(), ['reset_url' => $url]);

                Model_Annex_Email::factory()->send('mail.account.forgot', $user->email, $data);

                $this->template->main->content .= '<h2>Your reset link is on the way<h2>';
                $this->template->main->content .= '<div class="status-box success">We have sent you an email with a reset link.
                    Go check your inbox, and you can reset your password from that link.</div>';
            }
            else
            {
                $this->template->main->content .= '<h2>Something went wrong!<h2>';
                $this->template->main->content .= '<div class="status-box warning">We could not find account with that username or email!</div>';
            }
        }
        else
        {
            $this->template->main->content .= Theme::factory('views/forms/account/forgot');
        }
    }
}