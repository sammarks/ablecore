<?php

namespace AbleCore\Install\Helpers;

class Field {

	/**
	 * The definition for the field.
	 * @var array
	 */
	protected $definition = array();

	/**
	 * The name of the field.
	 * @var null|string
	 */
	protected $name = null;

	public function __construct($field_name, array $definition)
	{
		$this->definition = $definition;
		$this->name = $field_name;
	}

	/**
	 * Load
	 *
	 * @param string $field_name The name of the existing field.
	 *
	 * @return Field|bool Either the field object, or false if the field doesn't exist.
	 */
	public static function load($field_name)
	{
		$definition = field_info_field($field_name);
		if (!$definition) {
			return false;
		}
		return new self($field_name, $definition);
	}

	/**
	 * Exists
	 *
	 * @param string $field_name The field to check.
	 *
	 * @return bool Whether or not the field exists.
	 */
	public static function exists($field_name)
	{
		return (field_info_field($field_name) !== null);
	}

	/**
	 * Create
	 *
	 * @param string $field_name The name of the field to create.
	 *
	 * @return Field The field object, or the loaded field if it already exists.
	 */
	public static function create($field_name)
	{
		if (self::exists($field_name)) {
			$instance = self::load($field_name);
		} else {
			$instance = new self($field_name, array());
		}
		return $instance->setType();
	}

	/**
	 * Create and Save
	 *
	 * @param string $field_name The name of the field to create (or re-create).
	 * @param string $type       The type of field it is.
	 *
	 * @return Field
	 */
	public static function createAndSave($field_name, $type = FieldTypes::TEXT)
	{
		$instance = self::create($field_name);
		$instance->setType($type);
		return $instance->save();
	}

	/**
	 * Set Type
	 *
	 * @param string $type The type of field.
	 *
	 * @return $this
	 */
	public function setType($type = FieldTypes::TEXT)
	{
		$this->definition['type'] = $type;
		return $this;
	}

	/**
	 * Get Name
	 * @return null|string The field name.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get Label
	 * @return string|false The label for the field. False if it doesn't exist.
	 */
	public function getLabel()
	{
		if (array_key_exists('label', $this->definition)) {
			return $this->definition['label'];
		} else return false;
	}

	/**
	 * Save
	 *
	 * @return $this
	 * @throws \Exception
	 * @throws \FieldException
	 */
	public function save()
	{
		if (self::exists($this->name)) {
			field_update_field($this->definition);
		} else {
			field_create_field($this->definition);
		}

		return $this;
	}

} 
