<?php

abstract class Model_Annex_Content extends Model
{
    public static function overview()
    {
        $collections = BrassDB::instance()->db()->getCollectionNames();
        $list = [];

        foreach ( $collections as $collection )
        {
            if ( preg_match('/^brass/i', $collection) )
            {
                $model = preg_replace('/^brass_/i', '', $collection);
                $model = preg_replace('/[s|es]$/i', '', $model);
                $list[] = $model;
            }
        }
        sort($list);

        return $list;
    }

    public static function __callStatic($name, $arguments)
    {
        // we'll always pass an array to simulate named parameters
        $arguments = $arguments[0];

        var_dump($name);
        echo '<hr />';
        var_dump($arguments);

        if ( assert(isset($arguments['model'])) )
        {
            $model = ucfirst($arguments['model']);
            $driver = ucfirst(Kohana::$config->load('annex_core.driver'));
            $content = "Model_{$driver}_{$model}";

            if ( assert(class_exists($content)) )
            {
                $content = new $content;
                var_dump($content);
            }
        }

    }
}