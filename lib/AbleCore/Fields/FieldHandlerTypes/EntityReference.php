<?php

namespace AbleCore\Fields\FieldHandlerTypes;

use AbleCore\Fields\FieldValueHandler;
use AbleCore\Fields\FieldValueTypes\EntityReferenceFieldValue;

class EntityReference extends FieldValueHandler
{
	public static $configuration = array(
		'entityreference' => 'reference',
		'node_reference' => 'nodeReference',
	);

	public static function reference($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'target_id'))
			return null;

		$info = field_info_field($name);

		if (!isset($info['settings']['target_type'])) {
			trigger_error('Target type couldnt be found.', E_USER_WARNING);
			return null;
		}
		$target_type = $info['settings']['target_type'];

		return new EntityReferenceFieldValue($type, $value, $target_type);
	}

	public static function nodeReference($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'nid'))
			return null;

		return new EntityReferenceFieldValue($type, $value, 'node', 'nid');
	}
}
