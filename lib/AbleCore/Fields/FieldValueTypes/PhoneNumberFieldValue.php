<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;

class PhoneNumberFieldValue extends FieldValue {

	/**
	 * Generates an iPhone-friendly link to the telephone number.
	 * @return string
	 */
	public function link()
	{
		return l($this->value, 'tel:' . $this->number());
	}

	/**
	 * Generates an iPhone-friendly version of the telephone number.
	 * @return mixed
	 */
	public function number()
	{
		return str_replace(' x', ',', $this->value);
	}

}
