<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Authentication Driver
 */
class Authenticate_Rate_Exception extends Kohana_Exception
{

    public $timestamp;

    public function __construct($message, $timestamp)
    {
        $this->timestamp = $timestamp;

        parent::__construct($message);
    }
}