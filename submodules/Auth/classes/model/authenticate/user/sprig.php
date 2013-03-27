<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Sprig Authentication Model
 *
 * To be extended and completed to user's needs
 *
 * Remember to validate data before saving to database. Some validation rules are already taken care of by the _init() method.
 * Min_length and max_length are set for both username and password, both these fields are already required, and the username
 * will be checked on uniqueness. However, you might want to add additional rules to validate if username is alphanumeric for example.
 *
 * If defining logins and last_login fields, add the following to the _init() in the custom user model class:
 *
 *     $this->_fields += array(
 *         'logins' => new Sprig_Field_Integer(array(
 *             'default'  => 0,
 *             'editable' => FALSE,
 *         )),
 *         'last_login' => new Sprig_Field_Integer(array(
 *             'default'  => 0,
 *             'editable' => FALSE,
 *         )),
 */
abstract class Model_Authenticate_User_Sprig extends Sprig
{
    /**
     * Specify config name so password gets hashed correctly
     * (with the right salt pattern) when set in user
     */
    protected $_name = 'authenticate';

    /**
     * Set the username, password, and token fields
     */
    public function _init()
    {
        $this->_fields += [
            'username' => new Sprig_Field_Char([
                'empty'      => FALSE,
                'unique'     => TRUE,
                'min_length' => 4,
                'max_length' => 50,
            ]),
            'password' => new Sprig_Field_Password([
                'empty'      => FALSE,
                'hash_with'  => NULL,
                'min_length' => 5,
                'max_length' => 50,
                'callbacks'  => [
                    [$this, 'hash_password'],
                ],
            ]),
            'token' => new Sprig_Field_Char([
                'editable' => FALSE,
            ]),
        ];
    }

    /**
     * Hash callback using the A1 library
     *
     * @param   string  password to hash
     * @return  string
     */
    public function hash_password(Validate $array, $field)
    {
        $pass = $array[$field];
        $array[$field] = Authenticate::instance($this->_name)->hash($pass);
    }
}