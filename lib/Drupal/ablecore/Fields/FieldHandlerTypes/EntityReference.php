<?php

namespace Drupal\ablecore\Fields\FieldHandlerTypes;

use Drupal\ablecore\Fields\FieldValueHandler;
use Drupal\ablecore\Fields\FieldValueTypes\EntityReferenceFieldValue;

class EntityReference extends FieldValueHandler
{
	public static $configuration = array(
		'entityreference' => 'reference',
		'node_reference' => 'nodeReference',
		'field_collection' => 'fieldCollection',
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

	public static function fieldCollection($type, $value, $name)
	{
		if (!self::checkFieldValue($value, 'value'))
			return null;

		// TODO: Enable revision support for this when https://www.drupal.org/node/2075325 is fixed.
		return new EntityReferenceFieldValue($type, $value, 'field_collection_item', 'value');
	}
}
