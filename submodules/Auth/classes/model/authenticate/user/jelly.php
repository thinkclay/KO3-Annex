<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Authenticate Jelly Model
 */
abstract class Model_Authenticate_User_Jelly extends Jelly_Model
{
    /**
     * @param Jelly_Meta $meta
     */
    public static function initialize(Jelly_Meta $meta)
    {
        $meta->fields([
            'id' => new Field_Primary,
            'username' => Jelly::field('string', [
                'unique' => TRUE,
                    'rules' => [
                        ['not_empty'),
                        ['min_length', [':value', 4]],
                        ['max_length', [':value', 4]],
                    )
                ]),
            'password' => Jelly::field('password', [
                'hash_with' => ['Model_A1_User_Jelly', 'hash_password'],
                'rules' => [
                    ['not_empty'],
                    ['min_length', [':value', 6]],
                    ['max_length', [':value', 50]],
                )
            )),
            'password_confirm' => Jelly::field('password', [
                'in_db' => FALSE,
                'rules' => [
                    ['not_empty'],
                    ['min_length', [':value', 6]],
                    ['max_length', [':value', 50]],
                    ['matches', [':validation', 'password', ':field']]
                )
            )),
        ]);
    }

    /**
     * Hash callback using the A1 library
     *
     * @param string password to hash
     * @return string
     */
    public static function hash_password($password)
    {
        return Authenticate::instance()->hash($password);
    }
}