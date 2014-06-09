<?php

/**
 * Manages MVC Views.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore\Modules;

/**
 * Theme Manager
 *
 * Manages Drupal Theme hooks. Example usage:
 *
 *     $manager = new ThemeManager();
 *     $manager->define('theme_key', 'path');
 *     $manager->define('second_theme_key', 'path.to.theme');
 *     $manager->fin();
 *
 *     // > array(
 *     //    'theme_key' => array(
 *     //      'arguments' => array(),
 *     //      'template' => 'themes/path.tpl.php',
 *     //     ),
 *     //     'second_theme_key' => array(
 *     //      'arguments' => array(),
 *     //      'template' => 'themes/path/to/theme.tpl.php',
 *     //     ),
 *     //   )
 *
 * The above can also be accomplished by doing this...
 *
 *     ThemeManager::init()
 *      ->define('theme_key', 'path')
 *      ->define('second_theme_key', 'path.to.theme')
 *      ->fin();
 *
 * See [the documentation](/docs/modules/themes) for more information.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */
class ThemeManager
{

	/**
	 * Generated
	 *
	 * The generated array configuration.
	 * @var array
	 */
	private $generated = array();

	/**
	 * Module
	 *
	 * The name of the current module.
	 * @var bool|null
	 */
	private $module = null;

	/**
	 * Init
	 *
	 * Creates a new ThemeManager.
	 *
	 * @return ThemeManager.
	 */
	public static function init()
	{
		return new ThemeManager();
	}

	/**
	 * Define
	 *
	 * Adds a new theme hook definition to the ThemeManager.
	 *
	 * @param  string $key                      The key for the theme hook.
	 * @param  array  $additional_configuration An array of additional configuration options.
	 *
	 * @throws \Exception
	 * @return ThemeManager
	 */
	public function define($key, $additional_configuration = array())
	{
		// Generate the template path.
		$generated_path = str_replace('_', '-', $key);

		$module = $this->getModule();
		if (!$module) {
			throw new \Exception('A module name could not be found when adding the theme. You might have to specify it manually.');
		}

		$this->generated[$key] = array(
			'template' => $generated_path,
			'render element' => 'element',

			// Trick Drupal into sending some information that identifies this theme
			// as an Able Core theme so that we can perform some extra file-checking
			// magic inside hook_theme_registry_alter. The variable is removed inside
			// hook_theme_registry_alter.
			'variables' => array('ablecore' => array('module' => $module))
		);

		$this->generated[$key] = array_replace_recursive($this->generated[$key], $additional_configuration);

		return $this;
	}

	/**
	 * Define Function
	 *
	 * Defines a function to be used as a theme. Basically the same as define, just without
	 * the template auto-processing (which you won't need if you're using a function).
	 *
	 * @param string $key                      The name of the theme.
	 * @param string $render_element           The render element (whatever element is passed to the theme. Most likely
	 *                                         this will be 'element').
	 * @param string $file                     The filename the function lies in (relative to the module root).
	 * @param array  $additional_configuration Any additional configuration to pass.
	 *
	 * @return $this
	 */
	public function defineFunction($key, $render_element, $file, $additional_configuration = array())
	{
		$this->generated[$key] = array(
			'render element' => $render_element,
			'file' => $file,
		);
		$this->generated[$key] = array_replace_recursive($this->generated[$key], $additional_configuration);

		return $this;
	}

	/**
	 * Finish
	 *
	 * Finishes generation of the ThemeManager and returns the configuration
	 * array.
	 *
	 * @return array
	 */
	public function fin()
	{
		return $this->generated;
	}

	/**
	 * getModule
	 *
	 * Searches through the PHP Debug Backtrace to get the name of the module
	 * currently calling the Able Core libraries. This is so that developers
	 * don't have to specify the module name every time they invoke the libraries.
	 *
	 * This seems like the best way to do it with the tools available.
	 *
	 * @return bool|string The name of the module if successful, false otherwise.
	 */
	protected function getModule()
	{
		if ($this->module !== null) return $this->module;
		$backtrace = debug_backtrace();
		foreach ($backtrace as $item) {
			if (!array_key_exists('function', $item)) continue;
			$segments = explode('_', $item['function']);
			if (count($segments) <= 1) continue;
			if ($segments[count($segments) - 1] == 'theme') {
				$module_segments = array_slice($segments, 0, count($segments) - 1);
				return $this->module = implode('_', $module_segments);
			}
		}
		return $this->module = false;
	}

}
