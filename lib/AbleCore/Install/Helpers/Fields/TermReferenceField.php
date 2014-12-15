<?php

namespace AbleCore\Install\Helpers\Fields;

use AbleCore\Install\Helpers\Field;
use AbleCore\Install\Helpers\FieldTypes;

class TermReferenceField extends Field {

	public function setType($type = FieldTypes::TERM_REFERENCE)
	{
		return parent::setType($type);
	}

	/**
	 * Defines which vocabulary the term reference field references.
	 *
	 * @param string $vocabulary The machine name of the vocabulary.
	 * @param int    $parent     The TID of the parent term.
	 *
	 * @return $this
	 */
	public function references($vocabulary, $parent = 0)
	{
		return $this->setSetting('allowed_values', array(array(
			'vocabulary' => $vocabulary,
			'parent' => $parent,
		)));
	}

} 
