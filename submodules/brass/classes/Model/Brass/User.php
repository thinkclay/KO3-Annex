<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Brass user model
 */
class Model_Brass_User extends Model_Authenticate_User_Brass implements Acl_Role_Interface
{
    public $_fields = [
        'id' => [
            'type' => 'string'
        ],

        'role' => [
            'editable'  => 'admin',
            'label'     => 'Role',
            'type'      => 'string',
            'required'  => true,
        ],
        'created' => [
            'editable'  => FALSE,
            'type'      => 'string',
            'required'  => true,
        ],
        'updated' => [
            'editable'  => FALSE,
            'type'      => 'string'
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
        'phone1' => [
            'type' => 'string',
        ],
        'phone2' => [
            'type' => 'string',
        ],
        'address1' => [
            'type' => 'string',
        ],
        'address2' => [
            'type' => 'string',
        ],
        'city' => [
            'type' => 'string',
        ],
        'state' => [
            'type' => 'string',
        ],
        'postal_code' => [
            'type' => 'string',
        ],
        'country' => [
            'type' => 'string',
        ],

        'accredited' => [
            'type' => 'boolean',
        ],
        'account_id' => [
            'type' => 'string',
        ],
        'ssn_last2' => [
            'type'      => 'string'
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


        'old_investor_id' => [
            'type'  => 'string',
        ],



        // 'alt_phone' => [
        //     'type' => 'string',
        //     string '' (length=0)
        // ],
        // 'alt_phone_type' => [
        //     'type' => 'string',
        //     string '0' (length=1)
        // ],




        // 'ssn_last2' => [
        //     'type' => 'string',
        //     string '' (length=0)
        // ],
        // 'state' => [
        //     'type' => 'string',
        //     string '' (length=0)
        // ],
        // 'updated_at' => [
        //     'type' => 'string',
        //     string '2012-11-23 15:39:26' (length=19)
        // ],
        // 'user_id' => [
        //     'type' => 'string',
        //     string '1' (length=1)
        // ],


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