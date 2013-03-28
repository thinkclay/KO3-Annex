<?php

abstract class Model_Content extends Model
{
    public static function overview()
    {
        echo 'this will return a list of content types';
    }

    public static function create()
    {
        echo 'this will allow you to create content';
    }
}