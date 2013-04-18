<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Brass user model
 */
class Model_Brass_User extends Model_Authenticate_User_Brass implements Acl_Role_Interface
{
    public $_fields = [
        'role' => [
            'type'     => 'string',
            'required' => true,
        ],
        'created' => [
            'type'     => 'string',
            'required' => true,
        ],
        'last_login' => [
            'type'      => 'int',
        ],
        'updated_time' => [
            'type' => 'string',
        ],
        'login_count' => [
            'type' => 'int',
        ],
        'token' => [
            'type' => 'string',
        ],
        'username' => [
            'label'      => 'User Name',
            'type'       => 'string',
            'required'   => true,
            'min_length' => 4,
            'max_length' => 32,
            'unique'     => true,
        ],
        'password' => [
            'type'          => 'string',
            'required'      => TRUE,
            'min_length'    => 5,
            'max_length'    => 50
        ],
        'email' => [
            'type'       => 'string',
            'required'   => true,
            'min_length' => 4,
            'max_length' => 32,
            'unique'     => true,
            'rules' => [
                ['email'],
                ['email_domain'],
            ]
        ],
        'first_name' => [
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
        ],
        'middle_name' => [
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
        ],
        'last_name' => [
            'type'       => 'string',
            'max_length' => 32,
            'rules'      => [
                ['alpha_dash']
            ]
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