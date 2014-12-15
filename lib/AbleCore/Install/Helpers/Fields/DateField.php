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
	 * @param bool $year
	 * @param bool $month
	 * @param bool $day
	 * @param bool $hour
	 * @param bool $minute
	 * @param bool $second
	 *
	 * @return $this
	 */
	public function setGranularity($year = false, $month = false, $day = false, $hour = false, $minute = false, $second = false)
	{
		$year = $year ? 'year' : false;
		$month = $month ? 'month' : false;
		$day = $day ? 'day' : false;
		$hour = $hour ? 'hour' : false;
		$minute = $minute ? 'minute' : false;
		$second = $second ? 'second' : false;

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
