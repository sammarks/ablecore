<?php

namespace AbleCore\Fields\FieldHandlerTypes;

use AbleCore\Fields\FieldValue;
use AbleCore\Fields\FieldValueHandler;

class ExtraFields extends FieldValueHandler {

	public static $configuration = array(
		'telephone' => 'plaintext',
		'url' => 'url',
	);

	public static function plaintext($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'value')) {
			return null;
		}

		return new FieldValue($value, $value['value'], $type);
	}

	public static function url($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'value')) {
			return null;
		}

		$title = !empty($value['title']) ? $value['title'] : $value['value'];
		$attributes = !empty($value['attributes']) ? $value['attributes'] : array();

		return new FieldValue($value, l($title, $value['value'], array('attributes' => $attributes)), $type);
	}

}
