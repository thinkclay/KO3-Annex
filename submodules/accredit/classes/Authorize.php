<?php defined('SYSPATH') or die('No direct script access.');

/**
 * User Authorization
 *
 * This library offers advanced user authorization using a user defined Authentication library, and an
 * improved version of Zend's ACL for Kohana.
 *
 * The Access Control List (roles,resources,rules) and the desired Authentication library are stored in a
 * config file. Usage in your code (controller/libraries/models) are as follow:
 *
 *      // simple acl usage (resource string 'blog')
 *       if ( Authorize::instance()->allowed('blog','read') )
 *          // do
 *       else
 *          // don't
 *
 *       // advanced acl usage (resource object: $blog), allows using the improved assertions
 *       if ( Authorize::instance()->allowed($blog,'delete') )
 *           // do
 *       else
 *           // don't
 *
 */
class Authorize extends Acl
{
    /**
     * Config object
     */
    protected $_config;

    /**
     * Authentication instance
     */
    protected $_auth;

    /**
     * Return an instance of Authorize.
     *
     * @return  object
     */
    public static function instance($_name = 'authorize')
    {
        static $_instances;

        if ( ! isset($_instances[$_name]) )
        {
            $_instances[$_name] = new Authorize($_name);
        }

        return $_instances[$_name];
    }

    /**
     * Build default Authorize from config.
     *
     * @return  void
     */
    public function __construct($_name = 'authorize')
    {
        // Read config
        $this->_config = Kohana::$config->load($_name);

        // Create instance of Authenticate lib (authorize, auth, authlite)
        $instance = new ReflectionMethod($this->_config->lib['class'], 'instance');
        $params   = Arr::get($this->_config->lib, 'params', array());

        $this->_auth = $instance->invokeArgs(NULL, $params);

        // Guest role
        $this->_guest_role = $this->_config['guest_role'];

        // Add Guest Role as role
        if ( ! array_key_exists($this->_config['guest_role'], $this->_config['roles']) )
        {
            $this->add_role($this->_config['guest_role']);
        }

        // Load ACL data
        $this->load();
    }

    /**
     * Load ACL data (roles/resources/rules)
     *
     * This allows you to add context specific rules
     * roles and resources.
     *
     * @param  array|Kohana_Config  configiration data
     */
    public function load()
    {
        // Roles
        if ( isset($this->_config['roles']) )
        {
            foreach ( $this->_config['roles'] as $role => $parent)
            {
                $this->add_role($role, $parent);
            }
        }

        // Resources
        if ( isset($this->_config['resources']) )
        {
            foreach ( $this->_config['resources'] as $resource => $parent )
            {
                $this->add_resource($resource,$parent);
            }
        }

        // Rules
        if ( isset($this->_config['rules']) )
        {
            foreach ( ['allow', 'deny'] as $method )
            {
                if ( isset($this->_config['rules'][$method]) )
                {
                    foreach ( $this->_config['rules'][$method] as $rule )
                    {
                        // create variables
                        $role = $resource = $privilege = $assertion = NULL;

                        // extract variables from rule
                        extract($rule);

                        // create assert object
                        if ( $assertion )
                        {
                            if ( is_array($assertion) )
                            {
                                $assertion = count($assertion) === 2 ? new $assertion[0]($assertion[1]) : new $assertion[0];
                            }
                            else
                            {
                                $assertion = new $assertion;
                            }
                        }

                        // this is faster than calling $this->$method
                        if ( $method === 'allow' )
                        {
                            $this->allow($role, $resource, $privilege, $assertion);
                        }
                        else
                        {
                            $this->deny($role, $resource, $privilege, $assertion);
                        }
                    }
                }
            }
        }
    }

    /**
     * Check if logged in user (or guest) has access to resource/privilege.
     *
     * @param   mixed     Resource
     * @param   string    Privilege
     * @param   boolean   Override exception handling set by config
     * @return  boolean   Is user allowed
     * @throws  Authenticate_Exception   In exception modus, when user is not allowed
     */
    public function allowed($resource = NULL, $privilege = NULL, $exception = NULL)
    {
        if ( ! is_bool($exception) )
        {
            // take config value
            $exception = $this->_config['exception'];
        }

        // retrieve user
        $role = ($user = $this->_auth->get_user()) ? $user : $this->_config['guest_role'];

        $result = $this->is_allowed($role, $resource, $privilege);


        if ( ! $exception OR $result === TRUE )
        {
            return $result;
        }
        else
        {
            $resources = $privileges = $errors = array();

            if ( $resource !== NULL )
            {
                $resources[] = $resource instanceof Acl_Resource_Interface ? $resource->get_resource_id() : (string) $resource;
            }

            if ( $privilege !== NULL )
            {
                $privileges[] = $privilege;
            }

            $resources[]  = 'default';
            $privileges[] = 'default';

            foreach ( $resources as $r )
            {
                foreach ( $privileges as $p )
                {
                    if ( $message = Kohana::message('authorize', $r . '.' . $p) )
                    {
                        throw new $this->_config['exception_type']($message);
                    }
                }
            }

            // this only happens when someone has removed the 'default.default' error message from messages/authorize.php
            throw new Authenticate_Exception('No error messages defined');
        }
    }

    /**
     * Alias of the logged_in method
     */
    public function logged_in()
    {
        return $this->_auth->logged_in();
    }

    /**
     * Alias of the get_user method
     */
    public function get_user()
    {
        return $this->_auth->get_user();
    }

    /**
     * Returns authentication instance
     */
    public function auth()
    {
        return $this->_auth;
    }
}