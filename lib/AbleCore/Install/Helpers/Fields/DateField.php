<?php

namespace AbleCore\Install\Helpers\Fields;

use AbleCore\Install\Helpers\Field;
use AbleCore\Install\Helpers\FieldTypes;

class DateField extends Field {

	public function setType($type = FieldTypes::DATE)
	{
		return parent::setType($type);
	}

	/**
	 * Sets the granularity of the date field.
	 *
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @param string $hour
	 * @param string $minute
	 * @param string $second
	 *
	 * @return $this
	 */
	public function setGranularity($year = 'year', $month = 'month', $day = 'day', $hour = 'hour', $minute = 'minute', $second = 'second')
	{
		return $this->setSetting('granularity', array(
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'hour' => $hour,
			'minute' => $minute,
			'second' => $second,
		));
	}

	/**
	 * Enables the repeating capability for the field.
	 *
	 * @return $this
	 */
	public function enableRepeat()
	{
		return $this->setSetting('repeat', true);
	}

	/**
	 * Enables the "to-date" field.
	 *
	 * @param bool $required Whether or not the "to-date" is required.
	 *
	 * @return $this
	 */
	public function enableToDate($required = false)
	{
		if ($required) {
			return $this->setSetting('todate', 'required');
		} else {
			return $this->setSetting('todate', 'optional');
		}
	}

}
