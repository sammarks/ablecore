<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;
use AbleCore\Fields\FieldValueRegistry;

class FieldCollectionFieldValue extends FieldValue
{
	/**
	 * The ID of the field collection item.
	 * @var
	 */
	public $id;

	/**
	 * The revision ID of the field collection item.
	 * @var
	 */
	public $revision_id;

	/**
	 * The raw field collection information. Already loaded.
	 * @var
	 */
	public $raw_collection;

	public function __construct($type, $raw)
	{
		parent::__construct($raw, $raw['value'], $type);

		$this->id = $raw['value'];
		$raw_array = entity_load('field_collection_item', array($raw['value']));
		if (count($raw_array) > 0) {

			// The array from entity_load is keyed by entity id.
			foreach ($raw_array as $item) {
				$this->raw_collection = $item;
				break;
			}

		} else {
			throw new \Exception("The field collection '{$this->id}' does not exist.");
		}

		if (array_key_exists('revision_id', $raw))
			$this->revision_id = $raw['revision_id'];
	}

	public function __get($field)
	{
		if (!property_exists($this, $field)) {
			$value = FieldValueRegistry::field('field_collection_item', $this->raw_collection, $field);
			if ($value !== false) {
				return $value;
			} else {
				trigger_error("The property {$field} does not exist on this class.", E_USER_WARNING);
				return null;
			}
		} else {
			return $this->$field;
		}
	}

	public function field($name)
	{
		$args = func_get_args();
		array_shift($args);
		$func_args = array('field_collection_item', $this->raw_collection, $name);
		foreach ($args as $arg)
			$func_args[] = $arg;
		return forward_static_call_array(array('\AbleCore\Fields\FieldValueRegistry', 'field'), $func_args);
	}

	public function __toString()
	{
		return (string)$this->id;
	}
}
