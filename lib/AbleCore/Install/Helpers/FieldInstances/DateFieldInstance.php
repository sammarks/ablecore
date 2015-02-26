<?php

namespace AbleCore\Install\Helpers\FieldInstances;

use AbleCore\Install\Helpers\FieldInstance;

class DateFieldInstance extends FieldInstance {

	public function setDefaults()
	{
		$this->setWidgetSetting('input_format', 'm/d/Y - g:i:sa');
		$this->setWidgetSetting('label_position', 'none');
	}

	/**
	 * Enables the all day functionality of the date field.
	 *
	 * @return $this
	 */
	public function enableAllDay()
	{
		return $this->setWidgetSetting('display_all_day', true);
	}

}
