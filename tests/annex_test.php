<?php defined('SYSPATH') or die('No direct script access.');

class Annex_Test extends UnitTest_TestCase
{
    /**
     * Tests that we can process and read and delete a photo
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
}