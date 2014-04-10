<?php

/**
 * A page callback (path) manager for Drupal.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore\Modules;

/**
 * Page Callback (Path) Manager
 *
 * A menu hook manager for Drupal.
 *
 * #### Example Usage
 *
 *     PathManager::init()->define('path/with/two/arguments', 'file@function')->fin();
 *      action_function($arg1, $arg2) { ... } in controllers/file
 *
 *      // > array(
 *      //    'title' => '',
 *      //    'description' => '',
 *      //    'page callback' => 'function',
 *      //    'access arguments' => array('access content'),
 *      //    'file' => 'file',
 *      //   )
 *
 *     PathManager::init()->define('path/with/%/in/middle', 'file@function')->fin();
 *      action_function($arg1) { ... } in controllers/file
 *
 *      // > array(
 *      //    'title' => '',
 *      //    'description' => '',
 *      //    'page callback' => 'function'
 *      //    'page arguments' => array(2),
 *      //    'access arguments' => array('access content'),
 *      //    'file' => 'file',
 *      //   )
 *
 * See [the documentation](/docs/modules/menu) for more information.
 *
 * @package Able Core (Module Helpers)
 * @author  Samuel Marks <sam@sammarks.me>
 */
class PathManager
{

	/**
	 * Generated
	 *
	 * The generated routes to pass to Drupal.
	 * @var array
	 */
	private $generated = array();

	/**
	 * Access Arguments
	 *
	 * The current access arguments for defined routes.
	 * @var array
	 */
	private $access_arguments = null;

	/**
	 * Define
	 *
	 * Creates a new Menu hook entry.
	 *
	 * Here are some examples for the `$path` argument:
	 *
	 * - `path/to/page`
	 * - `path/%/with/%arguments`
	 *
	 * Here are some examples for the `$callback` argument:
	 *
	 * - `blog@index` - `action_index([$args ...])` in callbacks/blog.php
	 * - `blog@display` - `action_display([$args ...])` in callbacks/blog.php
	 * - `global@index` - `action_index([$args ...])` in callbacks/global.php
	 *
	 * @param string $path            The path for the new route. Examples:
	 * @param string $callback        The callback identifier for the new route. In the format
	 *                                of file@function (or a function that already exists).
	 * @param string $title           The title of the path.
	 * @param array  $extra_config    Any extra configuration options to add to the item.
	 * @param array  $extra_arguments The extra arguments to add to the page arguments item.
	 *
	 * @return PathManager
	 */
	function define($path, $callback, $title, $extra_config = array(), $extra_arguments = array())
	{
		// If the page callback is a function already, use it.
		if (function_exists($callback)) {
			$page_callback = $callback;
		} else {
			$segments = explode('@', $callback);

			// If we don't have enough segments, we'll assume it's a function that isn't loaded yet.
			if (count($segments) != 2) {
				$page_callback = $callback;
			} else {
				$page_callback_file = 'callbacks/' . $segments[0] . '.php';
				$page_callback = 'action_' . $segments[1];
			}
		}

		$arguments = array();
		$segments = explode('/', $path);
		foreach ($segments as $index => $segment) {
			if (strpos($segment, '%') === 0) {
				$arguments[] = $index;
			}
		}

		array_push($arguments, $extra_arguments);

		// Generate a list of access arguments.
		if (is_array($this->access_arguments) && count($this->access_arguments) > 0) {
			$access_arguments = $this->access_arguments;
		} else {
			$access_arguments = array('access content');
		}

		$generated_config = array(
			'title' => $title,
			'description' => '',
			'page callback' => $page_callback,
			'page arguments' => $arguments,
			'access arguments' => $access_arguments,
		);

		if (isset($page_callback_file)) {
			$generated_config['file'] = $page_callback_file;
		}

		// Tack on the title arguments if there is a title callback
		if (array_key_exists('title callback', $extra_config)) {
			$generated_config['title arguments'] = $arguments;
		}

		if (array_key_exists($path, $this->generated)) {
			trigger_error("The path: {$path} already exists in the menu config." .
			" You might want to fix that.",
				E_USER_WARNING);
		}

		$this->generated[$path] = array_replace_recursive($generated_config, $extra_config);

		return $this;
	}

	/**
	 * Init
	 *
	 * Creates a new RouteManager
	 *
	 * @return PathManager
	 */
	public static function init()
	{
		return new PathManager();
	}

	/**
	 * Access
	 *
	 * Sets the access arguments for chained define() calls until fin() is called.
	 *
	 * @param array $access_arguments The access arguments to set for all children until fin()
	 *                                is called.
	 *
	 * @return PathManager
	 * @throws InvalidAccessArgumentsException
	 */
	public function access($access_arguments)
	{
		if (!is_array($access_arguments) || count($access_arguments) <= 0) {
			throw new InvalidAccessArgumentsException('There were no access arguments passed.');
		}
		$this->access_arguments = $access_arguments;
		return $this;
	}

	/**
	 * Finish
	 *
	 * Finish the generation of the route or access level.
	 *
	 * @return array|PathManager The hook_menu configuration if not inside an access argument
	 *                           level. Otherwise, the access arguments are unset and the
	 *                           PathManager is returned.
	 */
	public function fin()
	{
		// If we're inside access arguments, unset it and return this object.
		if (is_array($this->access_arguments) && count($this->access_arguments) > 0) {
			$this->access_arguments = null;
			return $this;
		} else {
			return $this->generated;
		}
	}

}

class InvalidAccessArgumentsException extends \Exception {}
