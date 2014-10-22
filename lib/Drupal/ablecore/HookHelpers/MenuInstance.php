<?php

namespace Drupal\ablecore\HookHelpers;

class MenuInstance
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
	protected $access_arguments = array();

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
	 * @return self
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

		$arguments = array_merge($arguments, $extra_arguments);

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
	 * Access
	 *
	 * Sets the access arguments for chained define() calls until fin() is called.
	 *
	 * @param mixed    $access_arguments The access arguments to set for all children until fin()
	 *                                   is called.
	 * @param callable $routes_function  The function to be called to generate the routes for
	 *                                   that access. A MenuInstance is passed to the function.
	 *
	 * @return self
	 */
	public function access($access_arguments, callable $routes_function)
	{
		$instance = new static();
		$instance->setAccessArguments($access_arguments);
		$routes_function($instance);
		$this->generated += $instance->fin();

		return $this;
	}

	/**
	 * Sets the access arguments for the current instance from either a string
	 * or an array of access arguments.
	 *
	 * @param mixed $access_arguments Either a single access argument or an array
	 *                                of multiple arguments.
	 *
	 * @return $this
	 * @throws InvalidAccessArgumentsException
	 */
	public function setAccessArguments($access_arguments)
	{
		// If we have a string value for the access arguments, make it an array.
		if ($access_arguments && !is_array($access_arguments)) {
			$access_arguments = array($access_arguments);
		}

		if (!is_array($access_arguments) || count($access_arguments) <= 0) {
			throw new InvalidAccessArgumentsException('There were no access arguments passed.');
		}

		$this->access_arguments = $access_arguments;

		return $this;
	}

	/**
	 * Finish the generation of the menu callbacks.
	 *
	 * @return array The hook_menu configuration.
	 */
	public function fin()
	{
		return $this->generated;
	}

}

class InvalidAccessArgumentsException extends \Exception {}
