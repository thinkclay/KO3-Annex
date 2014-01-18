<?php

abstract class Model_Annex_Content extends Model
{

    public static function pagination($model, $per_page = 50)
    {
        $count = BrassDB::instance()->count(strtolower(Inflector::plural($model)), []);

        if ($count > $per_page)
        {
            $pages = ($count % $per_page) ? round($count / $per_page) + 1 : round($count / $per_page);
        }
        else
        {
            $pages = 0;
        }

        return [
            'count' => $count,
            'pages' => $pages
        ];
    }

    /**
     * List entries of passed model type in a table
     */
    public static function show_list($model, $template = NULL, $offset = 0, $limit = 0)
    {
        $driver = ucfirst(Kohana::$config->load('annex_core.driver'));
        $model = ucfirst($model);

        if ( ! class_exists('Model_'.$model))
        {
            $model = Inflector::singular($model);
        }

        $data = Brass::factory($model)->load($limit, ['_id' => -1], $offset)->as_array();
        $model_name = Inflector::singular($model);

        if ( $template )
            return Theme::factory($template)
                ->bind('model', $model_name)
                ->bind('data', $data);
        else if ( Theme::factory("views/content/model-list-$model", NULL, TRUE) )
            return Theme::factory("views/content/model-list-$model")
                ->bind('model', $model_name)
                ->bind('data', $data);
        else
            return Theme::factory('views/content/model-list-default')
                ->bind('model', $model_name)
                ->bind('data', $data);
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