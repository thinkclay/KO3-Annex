<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mango Authentication Driver
 */
class Authenticate_Mango extends Authenticate
{
    /**
     * Loads the user object from database using username
     *
     * @param   string   username
     * @return  object   User Object
     */
    protected function _load_user($username)
    {
        return Mango::factory(
            $this->_config['user_model'],
            [
                $this->_config['columns']['username'] => $username
            ]
        )->load();
    }

    /**
     * Saves the user object
     *
     * @param   object   User object
     * @return  void
     */
    protected function _save_user($user)
    {
        $user->update();
    }

    /**
     * Increment the number of logins of the user by 1
     *
     * @param   object   User object
     * @return  void
     */
    protected function _increment_logins($user)
    {
        $user->__get($this->_config['columns']['logins'])->increment(1);
    }

    /**
     * Increment the number of failed login attempts since last successfull login
     *
     * @param   object   User object
     * @return  void
     */
    protected function _increment_failed_attempts($user)
    {
        $user->__get($this->_config['columns']['failed_attempts'])->increment(1);
    }

    /**
     * Returns the number of failed login attempts
     *
     * @param   object   User object
     * @return  void
     */
    protected function _get_failed_attempts($user)
    {
        return $user->__get($this->_config['columns']['failed_attempts'])->as_int();
    }
}