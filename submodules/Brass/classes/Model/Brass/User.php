<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Brass user model
 */
class Model_Brass_User extends Model_Authenticate_User_Brass implements Acl_Role_Interface
{
    public $_fields = [
        'role' => [
            'editable' => 'admin',
            'type'     => 'string',
            'required' => true,
        ],
        'created' => [
            'editable'  => FALSE,
            'type'      => 'string',
            'required'  => true,
        ],
        'last_login' => [
            'editable'  => FALSE,
            'type'      => 'int',
        ],
        'updated_time' => [
            'editable' => FALSE,
            'type'     => 'string',
        ],
        'login_count' => [
            'editable'   => FALSE,
            'type'       => 'int',
        ],
        'token' => [
            'editable'   => FALSE,
            'type'       => 'string',
        ],
        'username' => [
            'editable'   => 'user',
            'label'      => 'User Name',
            'type'       => 'string',
            'required'   => true,
            'min_length' => 4,
            'max_length' => 32,
            'unique'     => true,
        ],
        'password' => [
            'editable'   => FALSE,
            'label'      => 'Password',
            'type'       => 'string',
            'required'   => TRUE,
            'min_length' => 5,
            'max_length' => 50
        ],
        'email' => [
            'editable'   => 'user',
            'label'      => 'Email Address',
            'type'       => 'string',
            'required'   => TRUE,
            'min_length' => 4,
            'max_length' => 32,
            'unique'     => TRUE,
            'rules' => [
                ['email'],
                ['email_domain'],
            ]
        ],
        'first_name' => [
            'editable'   => 'user',
            'label'      => 'First Name',
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
        ],
        'middle_name' => [
            'editable'   => 'user',
            'label'      => 'Middle Name',
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
        ],
        'last_name' => [
            'editable'   => 'user',
            'label'      => 'Last Name',
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
        ],
        'preferences' => [
            'editable'   => FALSE,
            'label'      => 'Preferences',
            'type'      => 'array'
        ]
    ];

    public function get_role_id()
    {
        return $this->role;
    }

    public static function username($val) {
        $regex = '/^[-a-z0-9_\@\.]++$/iD';
        return (bool) preg_match($regex, $val);
    }
}