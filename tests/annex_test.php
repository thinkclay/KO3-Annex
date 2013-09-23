<?php defined('SYSPATH') or die('No direct script access.');

class Annex_Test extends Unittest_TestCase
{
    /**
     * Tests that Annex will run
     *
     * @return null
     */
    public function test_annex_bootstrap()
    {
        // Make sure submodules were initialized
        $this->assertTrue(file_exists(ANXMODS.'accredit'.DIRECTORY_SEPARATOR.'readme.md'));
        $this->assertTrue(file_exists(ANXMODS.'brass'.DIRECTORY_SEPARATOR.'readme.md'));
        $this->assertTrue(file_exists(ANXMODS.'event'.DIRECTORY_SEPARATOR.'readme.md'));
        $this->assertTrue(file_exists(ANXMODS.'less'.DIRECTORY_SEPARATOR.'readme.md'));
    }

    /**
     * Test the Brass Module
     *
     * @return null
     */
    public function test_brass_module()
    {
        // Do we have all our classes?
        $this->assertTrue(class_exists('MongoClient'));
        $this->assertTrue(class_exists('Brass'));
        $this->assertTrue(class_exists('BrassDB'));

        // Is the driver working? Can we connect to the DB?
        $this->assertTrue(BrassDB::instance()->connected());
        $this->assertTrue(is_object(Brass::factory('Brass_User')));
        $this->assertTrue(isset(Brass::factory('Brass_User')->_fields));
    }

    /**
     * Test the Less Module
     *
     * @return null
     */
    public function test_less_module()
    {
        // Do we have the class?
        $this->assertTrue(class_exists('Less'));
    }

    /**
     * Test the Event Module
     *
     * @return null
     */
    public function test_event_module()
    {
        $this->assertTrue(class_exists('Event'));
    }

    /**
     * Test the Accredit Module
     *
     * @return null
     */
    public function test_accredit_module()
    {
        $this->assertTrue(class_exists('Authorize'));
        $this->assertTrue(class_exists('Authenticate'));
        $this->assertTrue(class_exists('ACL'));
    }

}