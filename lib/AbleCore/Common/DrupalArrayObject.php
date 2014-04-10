<?php

namespace AbleCore\Common;

class DrupalArrayObject
{
	protected $definition = array();
	protected $validations = array();

	function __construct($definition)
	{
		$this->definition = $definition;
	}

	/**
	 * Gets a key in the definition.
	 *
	 * @param string $key The key to get the value of.
	 *
	 * @return mixed|null The value of the key if it exists, else null.
	 */
	public function get($key)
	{
		if (array_key_exists($key, $this->definition)) {
			return $this->definition[$key];
		} else {
			return null;
		}
	}

	/**
	 * Sets a key in the definition to the specified value.
	 *
	 * @param string $key   The key to set.
	 * @param string $value The value to use.
	 */
	public function set($key, $value)
	{
		$this->definition[$key] = $value;
	}

	/**
	 * Validates the current definition.
	 *
	 * @return bool|string String if validation failed with the reason, otherwise true.
	 */
	public function validate()
	{
		foreach ($this->validations as $field => $validation) {
			if (!array_key_exists($field, $this->definition)) {
				return 'no ' . $field;
			}
			if ($validation == 'array' && !is_array($this->definition[$field])) {
				return $field . ' not array';
			}
			if ($validation == 'string' && !is_string($this->definition[$field])) {
				return $field . ' not string';
			}
			if ($validation == 'numeric' && !is_numeric($this->definition[$field])) {
				return $field . ' not numeric';
			}
		}

		return true;
	}
}
