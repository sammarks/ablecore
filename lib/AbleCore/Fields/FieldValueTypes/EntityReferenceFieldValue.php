<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;
use AbleCore\Fields\FieldValueRegistry;

class EntityReferenceFieldValue extends FieldValue
{
	/**
	 * The ID of the entity.
	 * @var
	 */
	public $id;

	/**
	 * The raw field entity information. Already loaded.
	 * @var
	 */
	public $raw_entity;

	/**
	 * The target type of the entity.
	 * @var
	 */
	public $target_type;

	public function __construct($type, $raw, $target_type)
	{
		parent::__construct($raw, $raw['target_id'], $type);

		$this->id = $raw['target_id'];
		$this->target_type = $target_type;
		$raw_array = entity_load($target_type, array($raw['target_id']));
		if (count($raw_array) > 0) {

			// The array from entity_load is keyed by entity id.
			foreach ($raw_array as $item) {
				$this->raw_entity = $item;
				break;
			}

		} else {
			throw new \Exception("The {$target_type} '{$this->id}' does not exist.");
		}
	}

	public function __get($field)
	{
		if (!property_exists($this, $field)) {
			$value = FieldValueRegistry::field($this->target_type, $this->raw_entity, $field);
			if ($value !== false) {
				return $value;
			} else {
				if (property_exists($this->raw_entity, $field)) {
					return $this->raw_entity->$field;
				} else {
					trigger_error("The property {$field} does not exist on this class.", E_USER_WARNING);
					return null;
				}
			}
		} else {
			return $this->$field;
		}
	}

	public function field($name)
	{
		$args = func_get_args();
		array_shift($args);
		$func_args = array($this->target_type, $this->raw_entity, $name);
		foreach ($args as $arg)
			$func_args[] = $arg;

		return forward_static_call_array(array('\AbleCore\Fields\FieldValueRegistry', 'field'), $func_args);
	}

	public function __toString()
	{
		return (string)$this->id;
	}
}
