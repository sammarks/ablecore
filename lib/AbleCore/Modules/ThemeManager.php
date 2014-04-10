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
	 * Example paths:
	 *
	 * - path => `themes/path.suffix`
	 * - folder.path => `themes/folder/path.suffix`
	 * - folder.subfolder.item => `themes/folder/subfolder/item.suffix`
	 *
	 * @param  string $key                      The key for the theme hook.
	 * @param  array  $additional_configuration An array of additional configuration options.
	 *
	 * @return ThemeManager
	 */
	public function define($key, $additional_configuration = array())
	{
		// Generate the template path.
		$generated_path = str_replace('_', '-', $key);

		$this->generated[$key] = array(
			'template' => $generated_path,
			'render element' => 'element',

			// Trick Drupal into sending some information that identifies this theme
			// as an Able Core theme so that we can perform some extra file-checking
			// magic inside hook_theme_registry_alter. The variable is removed inside
			// hook_theme_registry_alter.
			'variables' => array('ablecore' => 'yes'),
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

}
