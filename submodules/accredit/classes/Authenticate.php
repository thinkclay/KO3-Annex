<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * User Authentication using bcrypt
 *
 * bcrypt is highly recommended by many to safely store passwords. For more
 * information, see http://codahale.com/how-to-safely-store-a-password/
 */
abstract class Authenticate
{
    // Allowed salt characters
    const SALT = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    protected $_name;
    protected $_config;
    protected $_sess;
    protected $_user;

    /**
     * Return a static instance of Authenticate.
     *
     * @return  object
     */
    public static function instance($_name = 'authenticate')
    {
        static $_instances;

        if ( ! isset($_instances[$_name]) )
        {
            $_config = Kohana::$config->load($_name);
            $_driver = isset($_config['driver']) ? $_config['driver'] : 'Brass';
            $_class  = 'Authenticate_'.ucfirst($_driver);

            // $_instance['authenticate'] = new Authenticate_Brass('authenticate', [])
            $_instances[$_name] = new $_class($_name, $_config);
        }

        if ( CRYPT_BLOWFISH !== 1 )
            throw new Kohana_Exception('This server does not support bcrypt hashing');

        return $_instances[$_name];
    }

    /**
     * Loads Session and configuration options.
     *
     * @return  void
     */
    protected function __construct($_name = 'authenticate', $_config)
    {
        $this->_name       = $_name;
        $this->_config     = $_config;

        if (isset($this->_config['cookie']))
        {
            if ( ! isset($this->_config['cookie']['key']) )
            {
                $this->_config['cookie']['key'] = 'authenticate_{name}_autologin';
            }

            $this->_config['cookie']['key'] = strtr($this->_config['cookie']['key'], ['{name}' => $this->_name]);
        }

        if ( ! isset($this->_config['session']['key']) )
        {
            $this->_config['session']['key'] = 'authenticate_'.$this->_name;
        }
    }

    /**
     * (Initializes &) Returns the session we're working with
     *
     * @param    Force session id
     * @return   Session
     */
    public function session($id = NULL)
    {
        if ( ! isset($this->_sess))
        {
            $this->_sess = Session::instance($this->_config['session']['type'], $id);
        }

        return $this->_sess;
    }

    /**
     * Returns TRUE is a user is currently logged in
     *
     * @return  boolean
     */
    public function logged_in()
    {
        return is_object($this->get_user());
    }

    /**
     * Returns the user - if any
     *
     * @return  object / FALSE
     */
    public function get_user()
    {
        if ( ! isset($this->_user))
        {
            $this->_user = $this->find_user();
        }

        if (is_object($this->_user) AND $this->_config['prevent_browser_cache'] === TRUE)
        {
            // prevent browser caching of all responses when a user is logged in
            Response::factory()->headers('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            Response::factory()->headers('Pragma', 'no-cache');
        }

        return $this->_user;
    }

    /**
     * Sets the user that is logged in
     *
     * @return  object / FALSE
     */
    public function set_user($user)
    {
        $this->_user = $user;
    }

    /**
     * Finds the user in the session (if any)
     *
     * @return  object / FALSE
     */
    protected function find_user()
    {
        // Get the user from the session
        $user = $this->session()->get($this->_config['session']['key']);

        // User found in session, return
        if ( is_object($user) )
        {
            if ( $user->loaded() )
            {
                return $user;
            }
            else
            {
                // reloading failed - user is deleted but still exists in session
                // logout (so session & cookie are cleared)
                $this->logout(TRUE);
                return FALSE;
            }
        }

        if ( $this->_config['cookie']['lifetime'] )
        {
            if ( $token = Cookie::get($this->_config['cookie']['key']) )
            {
                list($hash, $username) = explode('.', $token, 2);

                if ( strlen($hash) === 32 AND $username !== NULL )
                {
                    // load user using username
                    if ( ! $user = $this->_load_user($username) )
                        return FALSE;

                    // validates token vs hash
                    if ( $user->loaded() AND $this->check($hash, $user->{$this->_config['columns']['token']}) )
                        return $this->complete_login($user, TRUE);
                }
            }
        }

        return FALSE;
    }

    /**
     * Registers a failed login attempt
     *
     * @param   Object   User object
     * @return  FALSE
     */
    public function failed_login($user)
    {
        if ( isset($this->_config['columns']['failed_attempts']) )
        {
            $this->_increment_failed_attempts($user);
        }

        if ( isset($this->_config['columns']['last_attempt']) )
        {
            $this->_set_last_attempt($user);
        }

        $this->_save_user($user);

        return FALSE;
    }

    /**
     * Updates session, set remember cookie (if required)
     *
     * @param   Object   User object
     * @param   boolean  Set 'remember me' cookie
     * @return  TRUE
     */
    public function complete_login($user, $remember = FALSE)
    {
        if ( $remember === TRUE && $this->_config['cookie']['lifetime'] )
        {
            $token = Text::random('alnum', 32);
            $user->{$this->_config['columns']['token']} = $this->hash($token);

            Cookie::set($this->_config['cookie']['key'], $token.'.'.$user->{$this->_config['columns']['username']}, $this->_config['cookie']['lifetime']);
        }

        if ( isset($this->_config['columns']['failed_attempts']) )
        {
            $this->_reset_failed_attempts($user);
        }

        if ( isset($this->_config['columns']['last_login']) )
        {
            $this->_set_last_login($user);
        }

        if ( isset($this->_config['columns']['logins']) )
        {
            $this->_increment_logins($user);
        }

        $this->_save_user($user);

        // Regenerate session (prevents session fixation attacks)
        $this->session()->regenerate();

        // Store user in session
        $this->store_user_in_session($user);

        return $this->_user = $user;
    }

    /**
     * Stores user model in session
     *
     * @param   user object
     * @return  void
     */
    public function store_user_in_session($user)
    {
        $this->session()->set($this->_config['session']['key'], $user);
    }

    /**
     * Attempt to log in a user.
     *
     * @param   string   username to log in
     * @param   string   password to check against
     * @param   boolean  enable auto-login
     * @return  mixed    user if succesfull, FALSE otherwise
     */
    public function login($username, $password, $remember = FALSE)
    {
        if ( empty($password) )
            return FALSE;


        $user = is_object($username) ? $username : $this->_load_user($username);

        if ( ! $user )
            return FALSE;

        if (
            isset($this->_config['columns']['failed_attempts']) AND
            isset($this->_config['columns']['last_attempt']) AND
            count(Arr::get($this->_config, 'rate_limits', []))
        )
        {
            // rate limiting active
            $attempt = 1 + (int) $this->_get_failed_attempts($user);
            $last    = isset($user->{$this->_config['columns']['last_attempt']}) ? $user->{$this->_config['columns']['last_attempt']} : NULL;

            if ( $attempt > 1 AND ! empty($last) )
            {
                ksort($this->_config['rate_limits']);

                foreach ( array_reverse($this->_config['rate_limits'], TRUE) as $attempts => $time )
                {
                    if ( $attempt > $attempts )
                    {
                        // user has to wait some more before being allowed to login again
                        if ( $last + $time > time() )
                            throw new Authenticate_Rate_Exception('Login not allowed. Rate limit active', $last + $time);
                        else
                            break;
                    }
                }
            }
        }

        return $this->check_password($user, $password) ? $this->complete_login($user, $remember) : $this->failed_login($user);
    }

    /**
     * Validates a password against a user. This can be used to confirm user in actions where
     * you ask for password while user is logged in to be extra safe (eg when deleting account)
     *
     *    if ( $authenticate->check_password($user, $this->request->post('password')))
     *    {
     *        // delete account or some other special action
     *    }
     *
     * @param   Model    User model
     * @param   String   Password
     * @return  boolean  Success
     */
    public function check_password($user, $password)
    {
        return $user->loaded() AND $this->check($password, $user->{$this->_config['columns']['password']});
    }

    /**
     * Log out a user by removing the related session variables.
     *
     * @param   boolean  completely destroy the session
     * @return  boolean
     */
    public function logout($destroy = FALSE)
    {
        unset($this->_user);

        if ( Cookie::get($this->_config['cookie']['key']) )
        {
            Cookie::delete($this->_config['cookie']['key']);
        }

        if ( $destroy === TRUE )
        {
            $this->session()->destroy();
        }
        else
        {
            $this->session()->delete($this->_config['session']['key']);
            $this->session()->regenerate();
        }

        return ! $this->logged_in();
    }

    /**
     * Generates bcrypt hash for input
     *
     * @param   string   value to hash
     * @param   string   salt (optional, will be generated if missing)
     * @param   int      cost (optional, will be read from config if missing)
     * @return  string   hashed input value
     */
    public function hash($input, $salt = NULL, $cost = NULL)
    {
        if ( ! $salt )
        {
            // Generate a random 22 character salt
            $salt = Text::random(self::SALT, 22);
        }

        if ( ! $cost )
        {
            $cost = $this->_config['cost'];
        }

        // Apply 0 padding to the cost, normalize to a range of 4-31
        $cost = sprintf('%02d', min(31, max($cost, 4)));

        // Create a salt suitable for bcrypt
        $salt = '$2a$'.$cost.'$'.$salt.'$';

        return crypt($input, $salt);
    }

    /**
     * Checks if password matches hash
     *
     * @param   string   password
     * @param   string   hashed password
     * @return  boolean  password matches hashed password
     */
    public function check($password, $hash)
    {
        // $2a$ (4) 00 (2) $ (1) <salt> (22)
        preg_match('/^\$2a\$(\d{2})\$(.{22})/D', $hash, $matches);

        // Extract the iterations and salt from the hash
        $cost = Arr::get($matches, 1);
        $salt = Arr::get($matches, 2);

        return $this->hash($password, $salt, $cost) === $hash;
    }

    /**
     * Saves the user object
     *
     * @param   object   User object
     * @return  void
     */
    protected function _save_user($user)
    {
        $user->save();
    }

    /**
     * Sets the last login field of the user object to current time
     *
     * @param   object   User object
     * @return  void
     */
    protected function _set_last_login($user)
    {
        $user->{$this->_config['columns']['last_login']} = time();
    }

    /**
     * Sets the last attempt field of the user object to current time
     *
     * @param   object   User object
     * @return  void
     */
    protected function _set_last_attempt($user)
    {
        $user->{$this->_config['columns']['last_attempt']} = time();
    }

    /**
     * Increment the number of logins of the user by 1
     *
     * @param   object   User object
     * @return  void
     */
    protected function _increment_logins($user)
    {
        $user->{$this->_config['columns']['logins']}++;
    }

    /**
     * Increment the number of failed login attempts since last successfull login
     *
     * @param   object   User object
     * @return  void
     */
    protected function _increment_failed_attempts($user)
    {
        $user->{$this->_config['columns']['failed_attempts']}++;
    }

    /**
     * Reset the number of failed login attempts
     *
     * @param   object   User object
     * @return  void
     */
    protected function _reset_failed_attempts($user)
    {
        unset($user->{$this->_config['columns']['failed_attempts']});
    }

    /**
     * Returns the number of failed login attempts
     *
     * @param   object   User object
     * @return  void
     */
    protected function _get_failed_attempts($user)
    {
        return $user->{$this->_config['columns']['failed_attempts']};
    }

    /**
     * Loads the user object from database using username
     *
     * @param   string   username
     * @return  object   User Object
     */
    abstract protected function _load_user($username);
}