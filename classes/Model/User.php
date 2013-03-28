<?php

class Model_User extends Model
{

    /**
     * Sends an email to the user email provided with a link back to account/complete_registration/$token
     *
     * @param   MangoObject Mango user object
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
            $message = Theme::view('default/emails/login/register')->bind('token', $token);

            if ( mail($to, $subject, $message, $headers) )
                return true;
            else
                return false;
        }
    }

    public static function create(array $post = array(), $role = null)
    {
        $errors = [];

        // initial validation
        $post = Validation::factory($post)
            ->rule('username', 'alpha_dash')
            ->rule('username', 'required')
            ->rule('password', 'required')
            ->rule('password_confirm', 'required')
            ->rule('password_confirm', 'matches', array(':validation', 'password', 'password_confirm'));

        $user = Mango::factory('Mango_User');

                if ($post->check())
                {
                    $post = $post->as_array();
                    $user->role = 'pending';
                    $user->created = time();

                    // create the account
                    $user->values($post);

                    try
                    {
                        // validate data
                        $user->check();
                        $user->create();
                        $check = static::email_verification($user);
                    }
                    catch (Validation_Exception $e)
                    {
                        $errors = $e->array->errors('account/user');
                    }
                }
                else
                {
                    $post['created'] = time();
                    $post['role'] = 'pending';

                    try
                    {
                        $user->check($post->as_array());
                    }
                    catch(Mango_Validation_Exception $e)
                    {
                        $errors = $e->array->errors('account/user');
                    }
                    // Validation failed, collect the errors
                    // make sure to get ALL errors
                    $errors = arr::merge($errors, $post->errors('account/user'));
                }
                return $errors;
            break;
    }
}