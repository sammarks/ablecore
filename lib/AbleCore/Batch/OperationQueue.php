<?php

namespace AbleCore\Batch;

abstract class OperationQueue {

	/**
	 * An internal array of batch operations.
	 * @var array
	 */
	protected $operations = array();

	/**
	 * The untranslated title to use as the title for the progress page.
	 * Defaults to 'Processing'
	 * @var string
	 */
	public $title = 'Processing';

	/**
	 * The untranslated message displayed while the processing is initialized.
	 * Defaults to 'Initializing'
	 * @var string
	 */
	public $init_message = 'Initializing';

	/**
	 * The untranslated message displayed while processing the batch. Available placeholders
	 * are @current, @remaining, @total, @percentage, @estimate and @elapsed.
	 * Defaults to 'Completed @current of @total'
	 * @var string
	 */
	public $progress_message = 'Completed @current of @total';

	/**
	 * The untranslated message displayed if an error occurred while processing the batch.
	 * Defaults to  'An error has occurred.'
	 * @var string
	 */
	public $error_message = 'An error has occurred.';

	/**
	 * Array of paths to CSS files to be used on the progress page.
	 * @var array
	 */
	public $css = array();

	/**
	 * Options passed to url() when constructing redirect URLs for the batch.
	 * @var array
	 */
	public $url_options = array();

	/**
	 * The function to call when the operation is finished.
	 * @var callable
	 */
	public $finished = '';

	public function __construct(array $operations = array())
	{
		$this->operations = $operations;
	}

	/**
	 * addOperation()
	 *
	 * Adds an operation to the current batch queue.
	 *
	 * @param Operation $operation The operation to add.
	 * @param mixed     ...        Additional arguments to pass to the execute function of the operation.
	 *
	 * @throws BatchException
	 */
	public function addOperation(Operation $operation)
	{
		$function_arguments = array_slice(func_get_args(), 1);
		if ($operation->type == 'internal') {
			if (!method_exists($operation, 'execute')) {
				throw new BatchException('The specified operation does not have an execute callback.');
			}
			$this->operations[] = array(array($operation, 'execute'), $function_arguments);
		} elseif ($operation->type == 'external') {
			if (function_exists($operation->callable)) {
				$this->operations[] = array($operation->callable, $function_arguments);
			} else {
				throw new BatchException('The function ' . $operation->callable . ' does not exist.');
			}
		}
	}

	/**
	 * start()
	 *
	 * Starts the batch queue.
	 *
	 * @throws BatchException
	 */
	public function start()
	{
		// Make sure we actually have some operations.
		if (count($this->operations) <= 0) {
			throw new BatchException('There are no operations to perform.');
		}

		$batch_configuration = array(
			'operations' => $this->operations,
			'title' => $this->title,
			'init_message' => $this->init_message,
			'progress_message' => $this->progress_message,
			'error_message' => $this->error_message,
			'css' => $this->css,
			'url_options' => $this->url_options,
		);

		// Update the finished function.
		if (function_exists($this->finished)) {
			$batch_configuration['finished'] = $this->finished;
		}

		batch_set($batch_configuration);
	}

} 

class BatchException extends \Exception {}
