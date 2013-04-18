<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Abstract Authentication User Model
 * To be extended and completed to user's needs
 *
 * Remember to validate data before saving to database. Some validation rules are already taken care of by the _fields declaration.
 * Min_length and max_length are set for both username and password, both these fields are already required, and the username
 * will be checked on uniqueness. However, you might want to add additional rules to validate if username is alphanumeric for example.
 */
abstract class Model_Authenticate_User_Brass extends Brass
{
    protected $_fields = [
        'username'   => [
            'type' => 'string',
            'required' => TRUE,
            'min_length' => 4,
            'max_length' => 50,
            'unique' => TRUE
        ],
        'password' => [
            'type'          => 'string',
            'required'      => TRUE,
            'min_length'    => 6,
            'max_length'    => 256
        ],
        'logins'          => ['type' => 'counter'],
        'last_login'      => ['type' => 'int'],
        'last_attempt'    => ['type' => 'int'],
        'failed_attempts' => ['type' => 'counter']
    ];

    // Specify config name so password gets hashed correctly (with the right salt pattern) when set in user
    protected $_name = 'authenticate';

    // On create, we want to hash the password
    // Due to an unresolved bug with the singleton we have to unset and reset the pass
    // To get the hashed pass to save to the db
    public function create($safe = TRUE)
    {
        $password = $this->hash($this->password);

        unset($this->password);

        $this->password = $password;

        return parent::create($safe);
    }

    public function update($criteria = [], $safe = TRUE)
    {
        if (isset($this->_changed['password']) )
        {
            $this->password = $this->hash($this->password);
        }

        return parent::update($criteria, $safe);
    }

    public function hash($password)
    {
        return Authenticate::instance($this->_name)->hash($password);
    }
}
