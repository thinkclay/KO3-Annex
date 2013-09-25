<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - An extension management module for Kohana
 *
 * @package     Annex
 * @category    Base
 * @author      Clay McIlrath
 */
class Annex_Helper
{
    public static function get_func_arg_names($funcName = null)
    {
        $trace = debug_backtrace();

        // If this was called from Brass, we want to use the caller before it
        if ( $trace[1]['class'] )
        {
            $caller = $trace[2];
        }

        $f = new ReflectionMethod($caller['class'], $caller['function']);
        $result = [];

        foreach ( $f->getParameters() as $param )
        {
            $result[] = $param->name;
        }

        return $result;
    }
}