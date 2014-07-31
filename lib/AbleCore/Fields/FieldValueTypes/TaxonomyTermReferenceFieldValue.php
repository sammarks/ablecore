<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Entity;

class TaxonomyTermReferenceFieldValue extends EntityReferenceFieldValue {

	public $value;

	public function __construct($type, $raw, $target_type)
	{
		parent::__construct($type, $raw, $target_type, 'tid');

		// For backwards compatibility purposes...
		$this->value = $this->raw_entity->base;
	}

}
