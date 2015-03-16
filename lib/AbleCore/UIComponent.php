<?php
/**
 * @package Able Core
 * @author  Graham Wheeler <graham@ableengine.com>
 */

namespace AbleCore;

/**
 * UIComponent
 *
 * UI Components are largely self-contained code, markup, and styles
 * stored under the active Drupal theme's components folder.
 *
 * @package Able Core
 * @author  Graham Wheeler <graham@ableengine.com>
 */
class UIComponent {

	/**
	 * fillContext
	 *
	 * Populates a context array with useful common variables
	 *
	 * @param $vars - A keyed array of context variables
	 */
	public static function fillContext(&$vars)
	{
		global $segments, $base_url, $theme_key, $user;

		// Cache results for the duration of the request since it will be invoked frequently
		$shared_context = &drupal_static(__FUNCTION__);
		if (!isset($shared_context)) {

			$theme_path = drupal_get_path('theme', $theme_key);
			$alias = isset($vars['node']->path) ? $vars['node']->path : drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
			$segments = explode('/', $alias );
			$major_section = array_shift($segments);
			$page_alias = (count($segments) > 0) ? array_pop($segments) : $major_section;
			$segments = explode('/', $alias);
			$shared_context['path'] = $alias;
			$shared_context['major_section'] = $major_section;
			$shared_context['page_alias'] = $page_alias;
			$shared_context['path_segments'] = $segments;
			$shared_context['base_url'] = $base_url;
			$shared_context['theme_path'] = $theme_path;
		}

		$vars = array_merge($vars, $shared_context);
	}

	/**
	 * Render
	 *
	 * Displays a component
	 *
	 * @param $component_name - The folder name of the component
	 * @param array $context - A set of context variables to expose to the component's template
	 * @param array $options - Additional options to expose to the template
	 * @return string - The rendered component markup
	 */
	public static function render($component_name, $context = array(), $options = array())
	{
		global $theme_key;
		$site_theme = variable_get('theme_default', $theme_key);
		try {
			if (!isset($component_name)) {
				throw new \Exception("Component not specified!");
			}
			$component_path = drupal_get_path('theme', $site_theme).'/components/'.$component_name;
			$component_file = DRUPAL_ROOT.'/'.$component_path.'/c.inc';
			if (!file_exists($component_file)) {
				throw new \Exception("Component {$component_name} not found at '{$component_file}'!");
			}
			if (!array_key_exists('path', $context)) {
				self::fillContext($context);
			}
			extract($context, EXTR_SKIP);
			extract($options, EXTR_SKIP);
			ob_start();
			include $component_file;
			return ob_get_clean();
		} catch (Exception $ex) {
			return $ex->getMessage();
		}
	}

}
