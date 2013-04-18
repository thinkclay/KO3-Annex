<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Abstract Authenticate User Model
 * To be extended and completed to user's needs
 * (see a2acldemo/classes/model/user.php for an example implementation)
 */
abstract class Model_Authenticate_User_ORM extends ORM
{

	// Authenticate config file name
	protected $_config = 'authenticate';

	// user model (from config)
	protected $_user_model;

	// user columns (from config)
	protected $_columns;

	protected function _initialize()
	{
		parent::_initialize();

		$this->_columns        = Kohana::$config->load($this->_config)->columns;
		$this->_user_model     = Kohana::$config->load($this->_config)->user_model;
	}

	public function save(Validation $validation = NULL)
	{
		if ( array_key_exists($this->_columns['password'], $this->_changed) )
		{
			$this->_object[$this->_columns['password']] = A1::instance($this->_config)->hash($this->_object[$this->_columns['password']]);
		}

		return parent::save($validation);
	}
}