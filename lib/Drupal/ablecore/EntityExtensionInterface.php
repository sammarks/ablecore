<?php

namespace Drupal\ablecore;

interface EntityExtensionInterface {

	/**
	 * Gets the entity type of the current class.
	 *
	 * @return string The entity type.
	 */
	static function getEntityType();

}
