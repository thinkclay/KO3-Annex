<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Annex Exception Handler
 *
 * @package     Annex
 * @category    Base
 * @author	    Clay McIlrath
 */
class Annex_Exception extends Kohana_Exception
{
    /**
     * Creates a new translated exception.
     *
     *     throw new Kohana_Exception('Something went terrible wrong, :user',
     *         array(':user' => $user));
     *
     * @param   string          $message    error message
     * @param   array           $variables  translation variables
     * @param   integer|string  $code       the exception code
     * @param   Exception       $previous   Previous exception
     * @return  void
     */
    public function __construct($message = NULL, array $variables = NULL, $code = 0, Exception $previous = NULL)
    {
        Model_Annex_Email::factory()->error_report($message, Request::$current);
        parent::__construct($message, $variables, $code, $previous);
    }
}