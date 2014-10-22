<?php

namespace Drupal\ablecore\ContentTypes;

use Drupal\ablecore\Common\DrupalArrayObject;

class ContentType extends DrupalArrayObject
{
	protected $validations = array(
		'type' => 'string',
		'name' => 'string',
		'description' => 'string',
		'base' => 'string',
		'custom' => 'numeric',
		'modified' => 'numeric',
		'locked' => 'numeric',
	);

	/**
	 * Creates a new content type definition.
	 *
	 * @param string $machine_name The machine name.
	 * @param string $human_name The human-readable name.
	 *
	 * @return ContentType
	 * @throws \Exception
	 */
	public static function create($machine_name, $human_name)
	{
		// Make sure they supplied a machine name...
		if (strlen($machine_name) <= 0)
			throw new \Exception("The machine name cannot be 0 characters long.");

		// Make sure the content type doesn't already exist.
		if (self::exists($machine_name))
			throw new \Exception("The content type '{$machine_name}' already exists. Use load() if you want to " .
				"load an existing content type.");

		$definition = array(
			'type' => $machine_name,
			'name' => st($human_name),
			'base' => 'node_content',
			'custom' => 1,
			'locked' => 1,
			'modified' => 1,
			'description' => '',
		);

		return new self($definition);
	}

	/**
	 * Loads an existing content type definition.
	 *
	 * @param string $machine_name The machine name to load.
	 *
	 * @return ContentType
	 * @throws \Exception
	 */
	public static function load($machine_name)
	{
		$def = node_type_get_type($machine_name);
		if ($def === false)
			throw new \Exception("The content type '{$machine_name}' does not exist.");

		return new self($def);
	}

	/**
	 * Determines if a content type exists or not.
	 *
	 * @param string $machine_name The machine name of the content type.
	 *
	 * @return bool
	 */
	public static function exists($machine_name)
	{
		return (node_type_get_type($machine_name) !== false);
	}

	/**
	 * Sets the description of the current content type.
	 *
	 * @param string $text The untranslated text to use.
	 */
	public function setDescription($text)
	{
		$this->set('description', st($text));
	}

	/**
	 * Saves the current content type to the database.
	 *
	 * @throws \Exception
	 */
	public function save()
	{
		// Make sure the definition is valid.
		$validation = $this->validate();
		if ($validation !== true)
			throw new \Exception("The content type is invalid. Reason: {$validation}. Cannot save.");

		$type = node_type_set_defaults($this->definition);
		node_type_save($type);
	}

	/**
	 * Deletes the current content type.
	 *
	 * @throws \Exception
	 */
	public function delete()
	{
		// Make sure the definition is valid.
		$validation = $this->validate();
		if ($validation !== true)
			throw new \Exception("The content type is invalid. Reason {$validation}. Cannot delete.");

		// Delete the content type.
		node_type_delete($this->definition['type']);
	}
}
