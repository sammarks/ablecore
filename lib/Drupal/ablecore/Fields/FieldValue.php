<?php

namespace Drupal\ablecore\Fields;

class FieldValue
{
	/**
	 * The raw array format for the field.
	 * @var array
	 */
	public $raw = array();

	/**
	 * The formatted value.
	 * @var string
	 */
	public $value = null;

	/**
	 * The field type.
	 * @var string
	 */
	public $type = null;

	public function __construct($raw, $value, $type)
	{
		$this->type = $type;
		$this->raw = $raw;
		$this->value = $value;
	}

	public function __toString()
	{
		if (is_string($this->value)) {
			return $this->value;
		} else {
			return '';
		}
	}
}
