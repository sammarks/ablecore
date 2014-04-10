<?php

namespace AbleCore\Fields\FieldHandlerTypes;

use AbleCore\Fields\FieldValueHandler;
use AbleCore\Fields\FieldValueTypes\FieldCollectionFieldValue;

class FieldCollection extends FieldValueHandler
{
	public static $configuration = array(
		'field_collection' => 'fieldCollection',
	);

	public static function fieldCollection($type, $value)
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;

		return new FieldCollectionFieldValue($type, $value);
	}
}
