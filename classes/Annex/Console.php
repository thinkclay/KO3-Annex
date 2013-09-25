<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex Console - A helper for logging data
 *
 * @package     Annex
 * @category    Base
 * @author      Clay McIlrath
 **/
class Annex_Console
{
    /**
     * Output - Gathers the errors and outputs as a view or data type
     *
     * @param   array   $data   the errors array
     * @param   string  $format json or view
     * @param   bool    $error  was it an error or message
     * @param   mixed   $code   data to render in pre tags
     */
    public static function output($data, $format, $error = TRUE, $code = FALSE, $styles = TRUE)
    {
        if ( count($data) >= 1 )
        {
            if ( $format == 'view' )
                return View::factory('console/output')
                    ->bind('data', $data)
                    ->bind('error', $error)
                    ->bind('code', $code)
                    ->bind('styles', $styles);

            elseif ( $format == 'json' )
                return json_encode($data);
        }
    }

    /**
     * Log - Logs the data to the error_log
     */
    public static function log($data)
    {
        error_log(var_export($data, TRUE));
    }
}