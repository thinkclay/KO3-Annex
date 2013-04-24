<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - An extension management module for Kohana
 *
 * @package		Annex
 * @category	Base
 * @author		Clay McIlrath
 **/
class Annex_Core
{
	/**
	 * Multi-dimentional data array that gets sent to the view
	 *
	 * @var	array
	 */
	public static $data;

	/**
	 * The existing kohana modules defined in bootstrap
	 *
	 * @var array
	 */
	protected $_kohana_modules;

	/**
	 * The kohana modules defined in annex init
	 *
	 * @var array
	 */
	protected $_annex_modules;

	/**
	 * Constructor - Sets our modules arrays and validates
	 *
	 * @param	array	$kohana_modules	modules defined in the core and bootstrap
	 * @param	array	$annex_modules	modules defined in the annex init
	 */
	public function __construct($kohana_modules, $annex_modules, $themes)
	{
		$this->_kohana_modules = $kohana_modules;
		$this->_annex_modules = $annex_modules;
		$modules = array_merge($kohana_modules, $annex_modules, $themes);

		// Initialize all of the modules with Kohana Core
		Kohana::modules($modules);

		// After initialization, we can check for dependencies
		$this->_validate_modules($annex_modules);
		$this->_validate_themes($themes);
	}

	/**
	 * Validate Modules - Checks each module for a config file and outputs
	 */
	private function _validate_modules($modules)
	{
		$installed = $failed = [];

		foreach ( $modules as $k => $v )
		{
			if ( $config = Kohana::$config->load(strtolower($k.'_annex.module')) )
			{
				if ( isset($config['name']) )
				{
					if ( isset($config['version']) )
					{
						$installed[] =
							"<big><strong>{$k} module</strong></big> - {$config['overview']}<br />\r\n".
							"<em>Version: {$config['version']}</em><br />\r\n";

						$this->_instantiate_module($k);
					}
				}
				else
				{
					$failed[] = $k.' the module is not properly named';
				}
			}
			else
			{
				$failed[] = $k.' config file was not found, or module settings were not defined';
			}
		}

		static::$data['modules_status'] = Console::output($installed, 'view', FALSE);
		static::$data['modules_status'] .= Console::output($failed, 'view');
	}

	/**
	 * Validate Modules - Checks each module for a config file and outputs
	 */
	private function _validate_themes($themes)
	{
		$installed = $failed = [];

		foreach ( $themes as $k => $v )
		{
			if ( $config = Kohana::$config->load(strtolower('theme_'.$k.'_annex')) )
			{
				if ( isset($config['name']) )
				{
					if ( isset($config['version']) )
					{
						$installed[] =
							"<big><strong>{$config['name']} theme</strong></big> - {$config['overview']}<br />\r\n".
							"<em>Version: {$config['version']}</em><br />\r\n";
					}
				}
				else
				{
					$failed[] = $k.' the theme is not properly named';
				}
			}
			else
			{
				$failed[] = $k.' theme config file was not found, or module / theme settings were not defined';
			}
		}

		static::$data['modules_status'] .= Console::output($installed, 'view', FALSE);
		static::$data['modules_status'] .= Console::output($failed, 'view');
	}

	/**
	 * Instantiate ACL
	 */
	private function _instantiate_module($module)
	{
		$private_config = Kohana::$config->load('authorize');
		$module_config = Kohana::$config->load($module.'_annex');

		if (isset($private_config) AND isset($module_config))
		{
			if (isset($private_config['resources']) AND isset($module_config['resources']))
			{
				$private_config->set(
					'resources',
					Arr::merge($private_config['resources'], $module_config['resources'])
				);
			}

			if (isset($private_config['rules']) AND isset($module_config['rules']))
			{
				$private_config->set(
					'rules',
					Arr::merge($private_config['rules'], $module_config['rules'])
				);
			}
		}
	}

	/**
	 * Annex Factory - Check the module for annex config and bootstrap
	 *
	 * @static
	 * @param	array	$kohana_modules	modules defined in the core and bootstrap
	 * @param	array	$annex_modules	modules defined in the annex init
	 */
	public static function factory($kohana_modules, $annex_modules, $themes)
	{
		return new Annex($kohana_modules, $annex_modules, $themes);
	}

	public static function render($page)
	{
		return View::factory($page)
			->bind('data', static::$data);
	}
}