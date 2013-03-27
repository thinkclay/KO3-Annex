<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Sprig Authentication Driver
 */
class Authenticate_Sprig extends Authenticate
{
    /**
     * Loads the user object from database using username
     *
     * @param   string  username
     * @return  object  User object
     */
    protected function _load_user($username)
    {
        return Sprig::factory(
            $this->_config['user_model'],
            [
                $this->_config['columns']['username'] => $username
            ]
        )->load();
    }

    /**
     * Saves the user object
     *
     * @param   object  User object
     * @return  void
     */
    protected function _save_user($user)
    {
        $user->update();
    }
}