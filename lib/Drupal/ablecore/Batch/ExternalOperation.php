<?php

namespace Drupal\ablecore\Batch;


class ExternalOperation extends Operation {

	public function __construct($callable)
	{
		if (!function_exists($callable)) {
			throw new BatchException('The callback function ' . $callable . ' does not exist.');
		}
		$this->callable = $callable;
		$this->type = self::OPERATION_TYPE_EXTERNAL;
	}

	public function execute(&$context)
	{
		// Forward the call to the external operation function.
		call_user_func_array($this->callable, func_get_args());
	}

} 
