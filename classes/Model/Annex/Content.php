<?php

abstract class Model_Annex_Content extends Model
{

    /**
     * List entries of passed model type in a table
     */
    public static function show_list($model, $template = NULL)
    {
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));

        $brass_model = 'Brass_'.ucfirst($model);

        if ( ! class_exists('Model_'.$brass_model) )
        {
            $brass_model = 'Brass_'.ucfirst(preg_replace('/[s|es]$/i', '', $model));
        }

        $data = Brass::factory($brass_model)->load(0)->as_array();
		$model_name = Inflector::singular($model);
		
        if ( $template )
        {
            return Theme::factory($template)
                ->bind('model', $model_name)
                ->bind('data', $data);
        }
        else if ( Theme::factory("views/content/model-list-$model", NULL, TRUE) )
        {
            return Theme::factory("views/content/model-list-$model")
                ->bind('model', $model_name)
                ->bind('data', $data);
        }
        else
        {
            return Theme::factory('views/content/model-list-default')
                ->bind('model', $model_name)
                ->bind('data', $data);
        }
    }

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