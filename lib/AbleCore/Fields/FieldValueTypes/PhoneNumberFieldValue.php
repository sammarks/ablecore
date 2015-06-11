<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;

class PhoneNumberFieldValue extends FieldValue {

	public function link()
	{
		return l($this->value, 'tel:' . $this->value);
	}

}
