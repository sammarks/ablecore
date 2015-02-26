<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;

class LongTextFieldValue extends FieldValue
{
	/**
	 * The safe value for the field.
	 * @var string
	 */
	public $safe_value;

	/**
	 * The unsafe value (directly from the database).
	 * @var string
	 */
	public $unsafe_value;

	/**
	 * The text format the content was entered in.
	 * @var string
	 */
	public $format;

	/**
	 * The safe summary of the contents.
	 * @var string
	 */
	public $safe_summary;

	/**
	 * The unsafe summary of the contents.
	 * @var string
	 */
	public $unsafe_summary;

	public function __construct($raw, $value, $type)
	{
		parent::__construct($raw, $value, $type);

		if (array_key_exists('safe_value', $raw))
			$this->safe_value = $raw['safe_value'];
		if (array_key_exists('value', $raw))
			$this->unsafe_value = $raw['value'];
		if (array_key_exists('format', $raw))
			$this->format = $raw['format'];
		if (array_key_exists('safe_summary', $raw))
			$this->safe_summary = $raw['safe_summary'];
		if (array_key_exists('summary', $raw))
			$this->unsafe_summary = $raw['summary'];
	}

	/**
	 * Gets a summary of the contents to a specified length.
	 *
	 * @param int $length The length to use. Defaults to null (Drupal default).
	 *
	 * @return string
	 */
	public function summary($length = null)
	{
		return text_summary($this->safe_value, $this->format, $length);
	}

	/**
	 * Gets a plaintext summary of the contents to a specified length.
	 *
	 * @param int $length The length to use. Defaults to null (Drupal default).
	 *
	 * @return string
	 */
	public function plain($length = null)
	{
		return text_summary(strip_tags($this->safe_value), $this->format, $length);
	}
}
