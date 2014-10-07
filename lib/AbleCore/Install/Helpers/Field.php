<?php

namespace AbleCore\Install\Helpers;

class Field {

	/**
	 * The definition for the field.
	 * @var array
	 */
	public $definition = array();

	/**
	 * The name of the field.
	 * @var null|string
	 */
	protected $name = null;

	public function __construct($field_name, array $definition)
	{
		$this->definition = $definition;
		$this->name = $field_name;

		$this->definition['field_name'] = $this->name;
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
		return new static($field_name, $definition);
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
		if (static::exists($field_name)) {
			$instance = static::load($field_name);
		} else {
			$instance = new static($field_name, array());
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
		$instance = static::create($field_name);
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
	 * Set Setting
	 *
	 * @param string $key The setting key.
	 * @param string $value The value for that key.
	 *
	 * @return $this
	 */
	public function setSetting($key, $value)
	{
		if (empty($this->definition['settings']) || !is_array($this->definition['settings'])) {
			$this->definition['settings'] = array();
		}
		$this->definition['settings'][$key] = $value;

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
	 * Save
	 *
	 * @return $this
	 * @throws \Exception
	 * @throws \FieldException
	 */
	public function save()
	{
		if (static::exists($this->name)) {
			field_update_field($this->definition);
		} else {
			field_create_field($this->definition);
		}

		return $this;
	}

} 
