<?php

namespace AbleCore\Fields\FieldHandlerTypes;
use AbleCore\Fields\FieldValueHandler;
use AbleCore\Fields\FieldValueTypes\DateFieldValue;

class Date extends FieldValueHandler
{
	public static $configuration = array(
		'datetime' => 'dateField',
		'date' => 'dateField',
		'datestamp' => 'dateField',
	);

	public static function dateField($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;
		if (!self::checkFieldValue($value, 'date_type'))
			return null;

		return new DateFieldValue($value, $value['value'], $type, $value['date_type']);
	}
}
