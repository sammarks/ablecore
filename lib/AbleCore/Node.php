<?php

/**
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;

class Node extends EntityExtension {

	/**
	 * Gets the entity type of the current class.
	 *
	 * @return string The entity type.
	 */
	static function getEntityType()
	{
		return 'node';
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
		$result = null;
		if (is_numeric($identifier)) {
			$result = parent::load($identifier);
		} else {
			// Try getting the UUID first, then the machine name.
			if (module_exists('uuid')) {
				$result = static::loadByUUID($identifier);
			} elseif (module_exists('defaultcontent') && function_exists('defaultcontent_get_default')) {
				$nid = defaultcontent_get_default($identifier);
				$result = static::load('node', $nid);
			} else {
				trigger_error("When loading the node: '{$identifier}', a non-number was given, " .
					"but only a number is supported.",
					E_USER_WARNING);
			}
		}

		if (!$result) {
			trigger_error("The node: '{$identifier}' does not exist.", E_USER_WARNING);
			return false;
		} else {
			return $result;
		}
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
		return static::load($identifier)->render($display);
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
		// If we're going to render the node, we need to render the full node.
		$this->base = node_load($this->id());
		$view = node_view($this->base, $display);
		return render($view);
	}

}
