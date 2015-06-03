<?php

namespace AbleCore\Fields;

class FieldValueHandler
{
	protected static function checkFieldValue($value, $index)
	{
		if (!is_array($value)) return false;
		if (!array_key_exists($index, $value)) return false;
		if (strlen($value[$index]) <= 0) return false;

		return true;
	}
}
