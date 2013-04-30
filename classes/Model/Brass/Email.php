<?php defined('SYSPATH') OR die('No direct access allowed.');

// put all logic and database stuff in here to conform with MVC rules
class Model_Brass_Email extends Brass
{
    protected $_fields = [
        'key' => [
            'label'     => 'Email Key',
            'editable'  => 'admin',
            'input'     => 'select',
            'type'      => 'string',
            'populate'  => 'Model_Annex_Form::email_keys'
        ],
        'subject' => [
            'label'     => 'Email Subject',
            'editable'  => 'admin',
            'input'     => 'text',
            'type'      => 'string',
        ],
        'body' => [
            'label'     => 'Email Body',
            'editable'  => 'admin',
            'input'     => 'textarea',
            'type'      => 'string',
            'attributes'=> [
                'class' => 'redactor'
            ]
        ]
    ];
}