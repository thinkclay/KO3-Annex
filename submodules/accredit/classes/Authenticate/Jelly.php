<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Jelly Authentication Driver
 */
class Authenticate_Jelly extends Authenticate
{
    /**
     * Loads the user object from database using username
     *
     * @param   string   username
     * @return  object   User Object
    */
    protected function _load_user($username)
    {
        $query = Jelly::select($this->_config['user_model'])
            ->where($this->_config['columns']['username'], '=', $username);

        if (isset($this->_config['columns']['active']))
        {
            $query = $query->where($this->_config['columns']['active'], '=', TRUE);
        }

        return $query->limit(1)->execute();
    }
}