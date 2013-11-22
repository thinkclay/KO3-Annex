<?php defined('SYSPATH') OR die('No direct script access.');

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
        $this->assertTrue(is_dir(ANXMODS.'accredit'.DIRECTORY_SEPARATOR.'classes'));
        $this->assertTrue(is_dir(ANXMODS.'brass'.DIRECTORY_SEPARATOR.'classes'));
        $this->assertTrue(is_dir(ANXMODS.'event'.DIRECTORY_SEPARATOR.'classes'));
        $this->assertTrue(is_dir(ANXMODS.'less'.DIRECTORY_SEPARATOR.'classes'));
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