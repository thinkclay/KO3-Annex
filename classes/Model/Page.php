<?php defined('SYSPATH') OR die('No direct access allowed.');

// put all logic and database stuff in here to conform with MVC rules
class Model_Page extends Brass
{
    protected $_fields = [
        'global' => [
            'type' => 'string'
        ],
        'controller' => [
            'type' => 'string',
            'required' => true,
        ],
        'action' => [
            'type' => 'string',
            'required' => true,
        ],
        'cms' => [
            'type' => 'array'
        ],
        'cms_global' => [
            'type' => 'array'
        ]
    ];

    public static function cms()
    {
        $cms = BrassDB::instance()->find_one('pages', [
            'controller' => strtolower(Request::$current->controller()),
            'action' => strtolower(Request::$current->action())
        ]);
        $cms = (is_array($cms)) ? $cms : [];

        $cms_global = BrassDB::instance()->find_one('pages', ['global' => 'true']);
        $cms_global = (is_array($cms_global)) ? $cms_global : [];

        return array_merge($cms, $cms_global);
    }
}