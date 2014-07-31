<?php

namespace AbleCore\Fields\FieldHandlerTypes;
use AbleCore\Fields\FieldValueHandler;

// Field value types.
use AbleCore\Fields\FieldValueTypes\LongTextFieldValue;
use AbleCore\Fields\FieldValueTypes\ImageFieldValue;
use AbleCore\Fields\FieldValueTypes\FileFieldValue;
use AbleCore\Fields\FieldValueTypes\TaxonomyTermReferenceFieldValue;
use AbleCore\Fields\FieldValue;

class Core extends FieldValueHandler
{
	public static $configuration = array(
		'file' => 'file',
		'image' => 'image',
		'imagefield_crop' => 'image',
		'list_integer' => 'simple',
		'list_float' => 'simple',
		'list_text' => 'simple',
		'list_boolean' => 'listBoolean',
		'number_integer' => 'simple',
		'number_decimal' => 'simple',
		'number_float' => 'simple',
		'taxonomy_term_reference' => 'term',
		'text' => 'simple',
		'text_long' => 'longText',
		'text_with_summary' => 'longText',
	);

	public static function file($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'uri'))
			return null;

		$processed = file_create_url($value['uri']);
		return new FileFieldValue($value, $processed, $type);
	}

	public static function image($type, $value, $name, $imageStyle = '')
	{
		if (!self::checkFieldValue($value, 'uri'))
			return null;

		if ($imageStyle !== '') {
			$processed = image_style_url($imageStyle, $value['uri']);
		} else {
			$processed = file_create_url($value['uri']);
		}
		return new ImageFieldValue($value, $processed, $type);
	}

	public static function listBoolean($type, $value, $name, $trueText = 'Yes', $falseText = 'No')
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;

		$processed = ($value['value']) ? $trueText : $falseText;
		return new FieldValue($value, $processed, $type);
	}

	public static function term($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'tid'))
			return null;

		return new TaxonomyTermReferenceFieldValue($type, $value, 'taxonomy_term');
	}

	public static function simple($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;

		return new FieldValue($value, $value['value'], $type);
	}

	public static function longText($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'safe_value'))
			return null;

		return new LongTextFieldValue($value, $value['safe_value'], $type);
	}
}
