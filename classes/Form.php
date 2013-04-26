<?php defined('SYSPATH') OR die('No direct script access.');

class Form extends Kohana_Form
{
    public static function text($name, $value = NULL, array $attributes = NULL)
    {
        // Set the input name
        $attributes['name'] = $name;

        // Set the input value
        $attributes['value'] = $value;

        if ( ! isset($attributes['type']))
        {
          // Default type is text
          $attributes['type'] = 'text';
        }

        return '<input'.HTML::attributes($attributes).' />';
    }

    public static function date($name, $value = NULL, array $attributes = NULL)
    {
        // Set the input name
        $attributes['name'] = $name;

        // Set the input value
        $attributes['value'] = $value;

        if ( ! isset($attributes['type']))
        {
          // Default type is text
          $attributes['type'] = 'text';
        }

        return '<input data-input="datepicker" '.HTML::attributes($attributes).' />';
    }

    public static function set($name, $value = NULL, array $attributes = NULL)
    {
        // Set the input name
        $attributes['name'] = $name;

        // Set the input value
        $attributes['value'] = $value;

        if ( ! isset($attributes['type']))
        {
          // Default type is text
          $attributes['type'] = 'text';
        }

        return '<input'.HTML::attributes($attributes).' />';
    }
}