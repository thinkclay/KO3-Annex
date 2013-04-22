<?php defined('SYSPATH') OR die('No direct script access.');

class Arr extends Kohana_Arr
{
    // cms.portfolio.header
    // [ cms => [ portfolio => [ header ]]]
    //
    // IT's MUTABLE!!!!!!!
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

}
