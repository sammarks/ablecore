<?php

namespace Drupal\ablecore\Batch;


abstract class Operation {

	const OPERATION_TYPE_INTERNAL = 1;
	const OPERATION_TYPE_EXTERNAL = 2;

	/**
	 * The operation type. Either OPERATION_TYPE_INTERNAL or OPERATION_TYPE_EXTERNAL.
	 * @var int
	 */
	public $type = self::OPERATION_TYPE_INTERNAL;

	/**
	 * If the type is OPERATION_TYPE_EXTERNAL, the name of the function to call when
	 * this operation is executed.
	 * @var string
	 */
	public $callable = '';

	public function __construct($type = 'internal', $callable = '')
	{
		$this->type = $type;
		$this->callable = $callable;
	}

	/**
	 * execute()
	 *
	 * Perform the operation. See
	 * https://api.drupal.org/api/drupal/modules%21system%21form.api.php/function/callback_batch_operation/7
	 * for more information.
	 *
	 * @param mixed ...      Arguments sent with the operation.
	 * @param array $context See https://api.drupal.org/api/drupal/modules%21system%21form.api.php/function/callback_batch_operation/7
	 */
	// Method definition not included in class.

} 
