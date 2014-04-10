<?php

/**
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;
use AbleCore\Fields\FieldValueRegistry;

/**
 * Node
 *
 * Helper class for managing Drupal nodes.
 *
 * See [the documentation](/docs/php-libraries/loading-nodes) for more information.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */
class Node extends DrupalExtension
{
	public function __construct($base)
	{
		$this->base = $base;
	}

	/**
	 * Import
	 *
	 * Creates a new instance of the Node class with an already-loaded node.
	 *
	 * @param object $existing_node The existing node.
	 *
	 * @return Node
	 */
	public static function import($existing_node)
	{
		return new self($existing_node);
	}

	public function __get($name)
	{
		$result = $this->field($name);
		if ($result === false) {
			return parent::__get($name);
		} else {
			return $result;
		}
	}

	/**
	 * Field
	 *
	 * Gets the value of the named field. Returns false in failure.
	 *
	 * @param string $name The name of the field to retrieve.
	 *
	 * @return mixed
	 */
	public function field($name)
	{
		$args = func_get_args();
		array_shift($args);
		$func_args = array('node', $this->base, $name);
		foreach ($args as $arg)
			$func_args[] = $arg;

		return forward_static_call_array(array('\AbleCore\Fields\FieldValueRegistry', 'field'), $func_args);
	}

	/**
	 * Load
	 *
	 * Loads an existing node with the specified identifier.
	 *
	 * @param  mixed $identifier  This can be a number or a string. If it's a number,
	 *                            it is sent through node_load as a NID. If it is a
	 *                            string, it is sent through defaultcontent to try and
	 *                            grab a node with a matching machine name.
	 *
	 * @return Node
	 */
	public static function load($identifier)
	{
		$base = null;
		if (is_numeric($identifier)) {
			$base = \node_load($identifier);
		} else {
			// Try getting the UUID first, then the machine name.
			if (function_exists('entity_uuid_load')) {
				$n = entity_uuid_load('node', array($identifier));
				$base = array_pop($n);
			} elseif (function_exists('defaultcontent_get_node')) {
				$base = \defaultcontent_get_node($identifier);
			} else {
				trigger_error("When loading the node: '{$identifier}', a non-number was given, " .
					"but only a number is supported.",
					E_USER_WARNING);
			}
		}

		if (!$base) {
			trigger_error("The node: '{$identifier}' does not exist.", E_USER_WARNING);
		} else {
			return new self($base);
		}

		return false;
	}

	/**
	 * Render
	 *
	 * Renders the current node.
	 *
	 * @param  string $display The display to use. Defaults to 'full'
	 *
	 * @return string           The HTML content for the node.
	 */
	public function render($display = 'full')
	{
		$view = node_view($this->base, $display); // Get around pass by reference warning.
		return render($view);
	}

	/**
	 * Get
	 *
	 * A shorthand for the rest of this class. Loads a node and renders it
	 * with or without the wrapper.
	 *
	 * @param  mixed  $identifier The identifier for the node.
	 *
	 * @see load()
	 *
	 * @param  string $display    The display to use when rendering the node. Defaults to 'full'
	 *
	 * @return string              The HTML content for the node.
	 */
	public static function get($identifier, $display = 'full')
	{
		return self::load($identifier)->render($display);
	}

	/**
	 * Alias
	 *
	 * Gets the path alias for the loaded node.
	 *
	 * @return bool|mixed|null The path alias.
	 */
	public function alias()
	{
		return drupal_get_path_alias('node/' . $this->nid);
	}

}
