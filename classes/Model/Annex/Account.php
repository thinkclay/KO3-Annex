<?php defined('SYSPATH') or die('No direct script access.');

class Model_Annex_Account
{

    /**
     * Sends an email to the user email provided with a link back to account/complete_registration/$token
     *
     * @param   BrassObject Brass user object
     * @param   Role Type
     * @return  void
     * @author  Winter King
     */
    public static function email_verification($user, $type = 'default')
    {
        if ( $type == 'default' )
        {
            $token = Encrypt::instance()->encode($user->email . '|' . $user->created);
            $subject = 'Registration';
            $to      = $user->email;
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: test@localhost' . "\r\n";
            $message = Theme::factory('emails/login/register')->bind('token', $token);

            if ( mail($to, $subject, $message, $headers) )
                return true;
            else
                return false;
        }
    }

    public static function create(array $post = [], $role = null)
    {
        // initial validation
        $post = Validation::factory($post)
            ->rule('username', 'alpha_dash')
            ->rule('username', 'required')
            ->rule('password', 'required')
            ->rule('password_confirm', 'required')
            ->rule('password_confirm', 'matches', [':validation', 'password', 'password_confirm']);


        $user = Brass::factory('Brass_User');
        $auth = Authenticate::instance();

        if ( $post->check() )
        {
            // create the account
            $user->created = time();
            $user->role = 'pending';
            $user->values($post->as_array());

            if ( $user->check() )
            {
                // $check = static::email_verification($user);

                if ( $user->create() )
                {
                    $auth->complete_login($user, TRUE);
                    return TRUE;
                }
            }
        }
    }

    public static function update(array $post, Model_Brass_User $user)
    {
        // initial validation
        $post = Validation::factory($post)
            ->rule('username', 'alpha_dash')
            ->rule('username', 'required')
            ->rule('password_confirm', 'matches', [':validation', 'password', 'password_confirm']);
        $post_data = $post->as_array();

        if ( ! $post_data['password'] )
            unset($post_data['password']);

        if ( $post->check() )
        {
            $doc = Brass::factory('Brass_User', ['_id' => $user->_id])->load();

            if ( $doc->loaded() )
            {
                $doc->values($post_data);
                $doc->update();
                Authorize::instance('private')->get_user()->reload();
            }
        }
    }
}