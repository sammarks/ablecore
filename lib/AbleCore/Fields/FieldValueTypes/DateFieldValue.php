<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValue;

class DateFieldValue extends FieldValue
{
	public $timestamp;
	public $timezone;
	public $dbTimezone;
	public $dateType;
	public $endDate;

	public function __construct($raw, $value, $type, $format)
	{
		parent::__construct($raw, $value, $type);

		switch ($format) {
			case 'datestamp':
				$this->dateType = 'unix';
				$this->timestamp = $value;
				break;
			case 'datetime':
				$this->dateType = 'datetime';
				$this->timestamp = strtotime($value);
				break;
			case 'date':
				$this->dateType = 'iso';
				$this->timestamp = strtotime($value);
				break;
		}

		if (array_key_exists('timezone', $raw))
			$this->timezone = $raw['timezone'];
		if (array_key_exists('timezone_db', $raw))
			$this->dbTimezone = $raw['timezone_db'];

		if (array_key_exists('value2', $raw)) {
			$enddateRaw = array(
				'timezone' => $this->timezone,
				'timezone_db' => $this->dbTimezone,
				'value' => $raw['value2'],
				'date_type' => $this->dateType,
			);
			$this->endDate = new DateFieldValue($enddateRaw, $raw['value2'], $type, $format);
		}
	}

	public function __toString()
	{
		return format_date($this->timestamp);
	}

	public function format($format)
	{
		return date($format, $this->timestamp);
	}

	public function drupalFormat($type = 'medium', $format = '', $timezone = NULL, $langcode = NULL)
	{
		return format_date($this->timestamp, $type, $format, $timezone, $langcode);
	}
}
