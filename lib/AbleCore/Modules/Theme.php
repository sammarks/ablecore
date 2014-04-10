<?php

/**
 * A View (not Drupal views) wrapper for MVC practices.
 *
 * This class literally is an alias for Drupal's theme() function,
 * but is here so that it can later be extended and to keep consistency
 * with the rest of the Able Core Libraries.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore\Modules;

/**
 * Theme
 *
 * This class literally is an alias for Drupal's `theme()` function,
 * but is here so that it can later be extended and to keep consistency
 * with the rest of the Able Core Libraries. Example usage:
 *
 *     Theme::make('key', array('variable1' => $value));
 *     Theme::make('key', array(
 *       'variable1' => 'value1',
 *       'nested_theme' => Theme::make('nested', array()),
 *     ));
 *
 * See [the documentation](/docs/modules/themes) for more information.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */
class Theme
{

	/**
	 * Make
	 *
	 * Makes a theme!
	 *
	 * @param  string $key        The theme key.
	 * @param  array  $variables  An array of variables to pass to the theme.
	 *
	 * @return string             The rendered theme.
	 */
	public static function make($key, $variables = array())
	{
		return \theme($key, $variables);
	}

}
