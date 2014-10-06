<?php

namespace AbleCore\Install\Helpers;

class FieldInstance {

	/**
	 * The definition of the field instance.
	 * @var array
	 */
	protected $definition = array();

	/**
	 * The associated field.
	 * @var Field|null
	 */
	protected $field = null;

	/**
	 * The associated bundle.
	 * @var string|null
	 */
	protected $bundle = null;

	/**
	 * The associated entity type.
	 * @var string|null
	 */
	protected $entity_type = null;

	public function __construct(Field $field, $bundle, $entity_type = 'node', array $definition = array())
	{
		$this->field = $field;
		$this->bundle = $bundle;
		$this->entity_type = $entity_type;
		$this->definition = $definition;

		$this->definition['field_name'] = $field->getName();
		$this->definition['entity_type'] = $this->entity_type;
		$this->definition['bundle'] = $this->bundle;
	}

	/**
	 * Load
	 *
	 * @param string $field_name  The field name.
	 * @param string $bundle      The bundle (content type).
	 * @param string $entity_type The type of entity the field is attached to.
	 *
	 * @return FieldInstance|bool Either the field instance object, or false.
	 */
	public static function load($field_name, $bundle, $entity_type = 'node')
	{
		$field = Field::load($field_name);
		$instance = field_info_instance($entity_type, $field_name, $bundle);
		if (!$instance || !$field) {
			return false;
		}
		return new self($field, $bundle, $entity_type, $instance);
	}

	/**
	 * Exists
	 *
	 * @param string $field_name  The field name.
	 * @param string $bundle      The bundle (content type).
	 * @param string $entity_type The entity type.
	 *
	 * @return bool Whether or not the field instance exists.
	 */
	public static function exists($field_name, $bundle, $entity_type = 'node')
	{
		return (field_info_instance($entity_type, $field_name, $bundle) !== null);
	}

	/**
	 * Create
	 *
	 * @param Field  $field       The field object.
	 * @param string $bundle      The bundle (content type).
	 * @param string $entity_type The type of entity.
	 *
	 * @return FieldInstance The new field instance object.
	 */
	public static function create(Field $field, $bundle, $entity_type = 'node')
	{
		if (self::exists($field->getName(), $bundle, $entity_type)) {
			$instance = self::load($field->getName(), $bundle, $entity_type);
		} else {
			$instance = new self($field, $bundle, $entity_type);
		}
		return $instance->setDefaults();
	}

	/**
	 * Set Defaults
	 *
	 * @return $this
	 */
	public function setDefaults()
	{
		$this->setRequired(false);
		return $this;
	}

	/**
	 * Set Label
	 *
	 * @param string $label The translated label for the field.
	 *
	 * @return $this
	 */
	public function setLabel($label)
	{
		$this->definition['label'] = $label;
		return $this;
	}

	/**
	 * Set Description
	 *
	 * @param string $description The translated description for the field.
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->definition['description'] = $description;
		return $this;
	}

	/**
	 * Set Required
	 *
	 * @param bool $required Whether or not the field is required.
	 *
	 * @return $this
	 */
	public function setRequired($required = false)
	{
		$this->definition['required'] = $required;
		return $this;
	}

	/**
	 * Set Weight
	 *
	 * @param int $weight The weight of the field.
	 *
	 * @return FieldInstance
	 */
	public function setWeight($weight)
	{
		return $this->setWidgetSetting('weight', $weight);
	}

	/**
	 * Set Default Value
	 *
	 * @param mixed $default_value The default value for the field.
	 *
	 * @return $this
	 */
	public function setDefaultValue($default_value)
	{
		$this->definition['default_value'] = $default_value;
		return $this;
	}

	/**
	 * Set Widget Setting
	 *
	 * @param mixed $key   The widget setting key.
	 * @param mixed $value The widget setting value.
	 *
	 * @return $this
	 */
	public function setWidgetSetting($key, $value)
	{
		self::verifyChildIsArray($this->definition, $key);
		$this->definition['widget'][$key] = $value;
		return $this;
	}

	/**
	 * Set Display Setting
	 *
	 * @param string $display The display to update the setting for.
	 * @param mixed  $key     The display setting key.
	 * @param mixed  $value   The display setting value.
	 *
	 * @return $this
	 */
	public function setDisplaySetting($display, $key, $value)
	{
		self::verifyChildIsArray($this->definition, 'display');
		self::verifyChildIsArray($this->definition['display'], $display);
		$this->definition['display'][$display][$key] = $value;
		return $this;
	}

	/**
	 * Set Setting
	 *
	 * @param mixed $key   The setting key.
	 * @param mixed $value The setting value.
	 *
	 * @return $this
	 */
	public function setSetting($key, $value)
	{
		self::verifyChildIsArray($this->definition, 'settings');
		$this->definition['settings'][$key] = $value;
		return $this;
	}

	/**
	 * Save
	 *
	 * @return $this
	 * @throws \FieldException
	 */
	public function save()
	{
		if (self::exists($this->field->getName(), $this->bundle, $this->entity_type)) {
			field_update_instance($this->definition);
		} else {
			field_create_instance($this->definition);
		}

		return $this;
	}

	/**
	 * Verify Child is Array
	 *
	 * Given $parent and $child, checks $parent to make sure $child exists
	 * and is an array.
	 *
	 * @param mixed  $parent The parent array or object.
	 * @param string $child  The child key.
	 */
	protected static function verifyChildIsArray(&$parent, $child)
	{
		if (!array_key_exists($child, $parent) || !is_array($parent[$child])) {
			$parent[$child] = array();
		}
	}

} 
