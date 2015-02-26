<?php

namespace AbleCore\Install\Helpers\Fields;

use AbleCore\Install\Helpers\Field;
use AbleCore\Install\Helpers\FieldTypes;

class FieldCollectionField extends Field {

	public function setType($type = FieldTypes::FIELD_COLLECTION)
	{
		return parent::setType($type);
	}

	public function __construct($field_name, array $definition)
	{
		parent::__construct($field_name, $definition);
		$this->setSetting('path', '');
		$this->setSetting('hide_blank_items', true);
	}

}
