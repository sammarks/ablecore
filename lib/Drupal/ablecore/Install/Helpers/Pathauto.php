<?php

namespace Drupal\ablecore\Install\Helpers;

class Pathauto {

	/**
	 * Set Pattern
	 *
	 * Sets a pathauto pattern for the specified content type.
	 *
	 * @param string $content_type The content type.
	 * @param string $pattern      The pattern.
	 */
	public static function setPattern($content_type, $pattern)
	{
		variable_set('pathauto_node_' . $content_type . '_pattern', $pattern);
	}

}
