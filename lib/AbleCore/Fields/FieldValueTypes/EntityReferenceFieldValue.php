<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Entity;
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

	public function __construct($type, $raw, $target_type, $target_key = 'target_id', $revision_key = false)
	{
		parent::__construct($raw, $raw[$target_key], $type);

		$this->id = $raw[$target_key];
		$this->target_type = $target_type;
		$this->raw_entity = Entity::loadWithType($target_type, $raw[$target_key]);

		// Set the revision if one was supplied.
		if ($revision_key !== false && array_key_exists($revision_key, $raw)) {
			$this->raw_entity->setRevision($raw[$revision_key]);
		}

		if (!$this->raw_entity) {
			trigger_error("The {$target_type} '{$this->id}' does not exist.", E_USER_WARNING);
		}
	}

	public function __get($field)
	{
		if (!property_exists($this, $field)) {
			return $this->raw_entity->$field;
		} else {
			return $this->$field;
		}
	}

	public function __call($method, $args)
	{
		if (!method_exists($this, $method)) {
			return call_user_func_array(array($this->raw_entity, $method), $args);
		} else {
			return call_user_func_array(array($this, $method), $args);
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
