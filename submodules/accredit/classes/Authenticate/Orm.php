<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Authentication Driver
 */
class Authenticate_ORM extends Authenticate
{
    /**
     * Loads the user object from database using username
     *
     * @param   string   username
     * @return  object   User Object
     */
    protected function _load_user($username)
    {
        return ORM::factory(
            $this->_config['user_model'],
            [
                $this->_config['columns']['username'] => $username
            ]
        );
    }
}