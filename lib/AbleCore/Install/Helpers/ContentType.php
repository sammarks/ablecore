<?php

namespace AbleCore\Install\Helpers;

class ContentType {

	/**
	 * The definition for the content type.
	 * @var object
	 */
	public $definition = null;

	/**
	 * The current list of enabled options for the content type.
	 * @var array
	 */
	protected $options = array(
		'status' => false,
		'sticky' => false,
		'promote' => false,
		'revision' => false,
	);

	/**
	 * @var bool Whether the content type was saved or not.
	 */
	protected $saved = false;

	public function __construct($name, $human_name)
	{
		$this->definition = (object)array(
			'type' => $name,
			'name' => $human_name,
			'base' => 'node_content',
			'custom' => 1,
			'locked' => 1,
			'modified' => 1,
		);
		$this->definition = node_type_set_defaults($this->definition);
		$this->setDefaultOptions(true, false);
	}

	/**
	 * Create
	 *
	 * Creates a new content type.
	 *
	 * @param string $name       The machine name of the content type.
	 * @param string $human_name The translated human name of the content type.
	 *
	 * @return ContentType The content type object.
	 */
	public static function create($name, $human_name)
	{
		return new static($name, $human_name);
	}

	/**
	 * Load
	 *
	 * @param string $name The name of the existing content type.
	 *
	 * @return bool|static Either a new instance of this class with the content type, or false.
	 */
	public static function load($name)
	{
		if ($content_type = node_type_load($name)) {
			$instance = new static($name, $content_type->name);
			$instance->definition = $content_type;
			return $instance;
		}

		return false;
	}

	/**
	 * Set Description
	 *
	 * @param string $description The translated description of the content type.
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->definition->description = $description;
		return $this;
	}

	/**
	 * Set Default Options
	 *
	 * @param bool $published Whether new nodes under this content type are published by default.
	 * @param bool $promoted  Whether new nodes under this content type are promoted to the front page
	 *                        by default.
	 * @param bool $sticky    Whether new nodes under this content type are stuck to the top of the content
	 *                        list by default.
	 * @param bool $revisions Whether new nodes under this content type have revisions enabled by default.
	 *
	 * @return $this
	 */
	public function setDefaultOptions($published = false, $promoted = false, $sticky = false, $revisions = false)
	{
		$this->options = array();
		if ($published) $this->options[] = 'status';
		if ($promoted) $this->options[] = 'promote';
		if ($sticky) $this->options[] = 'sticky';
		if ($revisions) $this->options[] = 'revision';

		$this->saveOptions();
		return $this;
	}

	/**
	 * Enable Option
	 *
	 * @param string $key The key of the content type option (default for new nodes).
	 *
	 * @return $this
	 */
	public function enableOption($key)
	{
		$this->options[$key] = true;
		$this->saveOptions();
		return $this;
	}

	/**
	 * Disable Option
	 *
	 * @param string $key The key of the content type option (default for new nodes).
	 *
	 * @return $this
	 */
	public function disableOption($key)
	{
		$this->options[$key] = false;
		$this->saveOptions();
		return $this;
	}

	/**
	 * Save
	 *
	 * Saves the content type.
	 *
	 * @return $this
	 */
	public function save()
	{
		node_type_save($this->definition);
		$this->saveOptions();
		$this->saved = true;

		return $this;
	}

	/**
	 * Add Body Field
	 *
	 * @param string $label The label of the body field.
	 *
	 * @return array The body field instance.
	 * @throws \Exception
	 */
	public function addBodyField($label = 'Body')
	{
		if (!$this->saved) {
			throw new \Exception('The content type must be saved before attempting to add a body field to it.');
		}
		return node_add_body_field($this->definition, $label);
	}

	/**
	 * Add Field
	 *
	 * Adds a field and associates it with the existing content type. This function does not
	 * save the created field instance.
	 *
	 * @param Field  $field    The field to attach to the content type.
	 * @param string $type     The class to create.
	 * @param string $label    The translated label for the field. If this is null, the default field
	 *                         label is used.
	 * @param bool   $required Whether or not the field is required.
	 * @param int    $weight   The weight of the field.
	 *
	 * @return FieldInstance The new field instance, not saved.
	 *
	 * @throws \Exception
	 */
	public function addField(Field $field, $label = null, $type = 'FieldInstance', $required = false, $weight = 1)
	{
		$class = '\\AbleCore\\Install\\Helpers\\' . $type;
		if (!class_exists($class)) {
			$class = '\\AbleCore\\Install\\Helpers\\FieldInstances\\' . $type;
			if (!class_exists($class)) {
				throw new \Exception('The field type ' . $type . ' does not exist.');
			}
		}

		/** @var FieldInstance $instance */
		$instance = forward_static_call(array($class, 'create'), $field, $this->definition->type);
		$instance->setLabel($label);
		$instance->setRequired($required);
		$instance->setWeight($weight);
		return $instance;
	}

	/**
	 * Save Options
	 *
	 * Saves the options for the content type to the variables table.
	 */
	protected function saveOptions()
	{
		$options = array();
		foreach ($this->options as $key => $enabled) {
			if ($enabled) {
				$options[] = $key;
			}
		}

		variable_set('node_options_' . $this->definition->name, $options);
	}

} 
