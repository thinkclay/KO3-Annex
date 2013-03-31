<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Brass - An ORM Layer for MongoDB
 *
 * @package     Annex
 * @category    Brass
 * @author      Clay McIlrath
 **/

interface Brass_Interface
{
	public function as_array($clean = TRUE);

	public function changed($update, array $prefix = []);

	public function saved();
}