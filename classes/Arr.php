<?php defined('SYSPATH') OR die('No direct script access.');

class Arr extends Kohana_Arr
{
    /**
     * Array from Character
     *
     * Builds an array like explode does, based on a specific character, however it builds it multidimensionally
     * and it takes a pointer so that this function is mutable
     *
     *  cms.portfolio.header becomes [cms => [portfolio => [header]]]
     *
     * @param string    $char The   character or delimiter to explode on
     * @param array     $array_ptr  the referenced array pointer to modify
     * @param string    $key        an internal param for recursively building the array
     * @param string    $value      an internal param for tracking the value of the final nested key
     */
    public static function from_char($char, &$array_ptr, $key, $value)
    {
        $keys = explode($char, $key);

        // extract the last key
        $last_key = array_pop($keys);

        // walk/build the array to the specified key
        while ( $arr_key = array_shift($keys) )
        {
            if ( ! array_key_exists($arr_key, $array_ptr) )
            {
                $array_ptr[$arr_key] = array();
            }
            $array_ptr = &$array_ptr[$arr_key];
        }

        // set the final key
        $array_ptr[$last_key] = $value;
    }


    public static function hash_to_csv_columns( array &$fields, $delimiter = ',', $enclosure = '"', $enclose_all = false )
    {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');
        $output = $keys = [];

        foreach ( $fields as $k => $v )
        {
            $output[] = trim($k);
        }

        return implode($delimiter, $output);
    }

    /**
     * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
     * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
     */
    public static function hash_to_csv( array &$fields, $delimiter = ',', $enclosure = '"', $enclose_all = false )
    {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');
        $output = [];


        foreach ( $fields as $field )
        {
            if ( is_array($field) or is_object($field) )
                $field = 'nested data';

            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ( $enclose_all or preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", (string) $field ) )
                $output[] = $enclosure.str_replace($enclosure, $enclosure . $enclosure, $field).$enclosure;
            else
                $output[] = trim($field);
        }

        return implode($delimiter, $output);
    }

}
