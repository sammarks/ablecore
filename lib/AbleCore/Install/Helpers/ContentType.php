<?php

namespace AbleCore\Install\Helpers;

class ContentType {

	/**
	 * The definition for the content type.
	 * @var array|object
	 */
	public $definition = array();

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

	public function __construct($name, $human_name)
	{
		$this->definition = array(
			'type' => $name,
			'name' => $human_name,
			'base' => 'node_content',
			'custom' => 1,
			'locked' => 0,
			'modified' => 1,
		);
		$this->definition = node_type_set_defaults($this->definition);
		$this->setDefaultOptions(true, false);
	}

	/**
	 * Init
	 *
	 * Creates a new content type.
	 *
	 * @param string $name       The machine name of the content type.
	 * @param string $human_name The translated human name of the content type.
	 *
	 * @return ContentType The content type object.
	 */
	public static function init($name, $human_name)
	{
		return new self($name, $human_name);
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
		$this->definition['description'] = $description;
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
	 */
	public function save()
	{
		node_type_save($this->definition);
		$this->saveOptions();
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

		variable_set('node_options_' . $this->definition['name'], $options);
	}

} 
