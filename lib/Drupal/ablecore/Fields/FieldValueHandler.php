<?php

namespace Drupal\ablecore\Fields;

class FieldValueHandler
{
	protected static function checkFieldValue($value, $index)
	{
		if (!array_key_exists($index, $value)) return false;
		if (strlen($value[$index]) <= 0) return false;

		return true;
	}
}
