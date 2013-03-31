<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Brass - An ORM Layer for MongoDB
 *
 * @package     Annex
 * @category    Brass
 * @author      Clay McIlrath
 **/

class Brass_Validation_Exception extends Validation_Exception
{
	/**
	 * @var  string  Name of model
	 */
	public $model;

	/**
	 * @var  int  Sequence number of model (if applicable)
	 */
	public $seq;

	public function __construct($model, Validation $array, $message = 'Failed to validate array', array $values = NULL, $code = 0)
	{
		$this->model = $model;

		parent::__construct($array, $message, $values, $code);
	}
}