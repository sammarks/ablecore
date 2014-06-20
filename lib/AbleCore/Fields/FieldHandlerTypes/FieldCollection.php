<?php

namespace AbleCore\Fields\FieldHandlerTypes;

use AbleCore\Fields\FieldValueHandler;
use AbleCore\Fields\FieldValueTypes\FieldCollectionFieldValue;

class FieldCollection extends FieldValueHandler
{
	public static $configuration = array(
		'field_collection' => 'collection',
	);

	public static function collection($type, $value)
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;

		return new FieldCollectionFieldValue($type, $value);
	}
}
