<?php defined('SYSPATH') or die('No direct script access.');

class Model_Annex_Account
{

    /**
     * Sends an email to the user email provided with a link back to account/complete_registration/$token
     *
     * @param   BrassObject Brass user object
     * @param   Role Type
     * @return  void
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

    public static function generate_token($user, $expiration)
    {
        $token = urlencode($user->email).'-'.md5($user->email.time().'myM@g1calS4lt').'-'.$expiration;
        $user->token = $token;
        $user->update();

        return $token;
    }

    /**
     * Find a user based on username being an actual username OR an email
     *
     *
     * @return mixed $user | false
     */
    public static function find_user($username)
    {
        // var_dump(new MongoId($username)); exit;
        // Check on username first
        $user = Brass::factory('Brass_User', ['username' => $username])->load();

        if ( $user->loaded() )
            return $user;

        // Check for email instead
        $user = Brass::factory('Brass_User', ['email' => $username])->load();

        if ( $user->loaded() )
            return $user;

        $user = Brass::factory('Brass_User', ['_id' => $username])->load();

        if ( $user->loaded() )
            return $user;

        return FALSE;
    }

    /**
     * Create User
     *
     * @param  array   $post        the post array
     * @param  string  $role        role type
     * @param  string  $response    how do we want to return the success/errors (array, bool)?
     */
    public static function create(array $post = [], $role = 'pending', $response = 'array')
    {
        $user = Brass::factory('Brass_User');

        // initial validation
        $post_validation = Validation::factory($post)
            ->rule('username', 'required')
            ->rule('username', [$user, 'unique_username'])
            ->rule('email', [$user, 'unique_email'])
            ->rule('password', 'required')
            ->rule('password_confirm', 'required')
            ->rule('password_confirm', 'matches', [':validation', 'password', 'password_confirm']);

        if ( $post_validation->check() )
        {
            // create the account
            $user->created = time();
            $user->role = $role;
            $user->values($post_validation->as_array());

            try
            {
                if ( $user->check() )
                {
                    // $check = static::email_verification($user);

                    if ( $user->create() )
                    {
                        Authenticate::instance()->login($post['username'], $post['password'], TRUE);

                        if ( $response == 'array' )
                            return [
                                'status' => 'success',
                                'message' => 'user created successfully'
                            ];
                        else
                            return TRUE;
                    }
                }
            }
            catch (Brass_Validation_Exception $e)
            {
                if ( $response == 'array' )
                    return [
                        'status' => 'error',
                        'message' => 'user creation failed due to user errors',
                        'errors' => $e->array->errors()
                    ];
                else
                    return FALSE;
            }
        }
        else
        {
            if ( $response == 'array' )
                return [
                    'status' => 'error',
                    'message' => 'user creation failed due to form errors',
                    'errors' => $post_validation->errors()
                ];
            else
                return FALSE;
        }
    }

    public static function update(array $post, $uid)
    {
        // initial validation
        $post = Validation::factory($post)
            ->rule('username', 'required')
            ->rule('username', [$user, 'unique_username'])
            ->rule('email', [$user, 'unique_email'])
            ->rule('password_confirm', 'matches', [':validation', 'password', 'password_confirm']);

        $post_data = $post->as_array();

        if ( ! $post_data['password'] )
            unset($post_data['password']);

        if ( $post->check() )
        {
            $doc = Brass::factory('Brass_User', ['_id' => $uid])->load();

            if ( $doc->loaded() )
            {
                $doc->values($post_data);
                $doc->update();
                Authorize::instance()->get_user()->reload();

                return TRUE;
            }
        }

        return FALSE;
    }
}