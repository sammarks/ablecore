<?php

namespace AbleCore\Install;

interface TaskInterface {

	public function __construct(TaskRunnerInterface $taskRunner);

	/**
	 * Run
	 *
	 * Runs the current task.
	 *
	 * @return bool TRUE if the task was successful, FALSE if not.
	 */
	public function run();

}

abstract class Task implements TaskInterface {

	/**
	 * The task runner executing the current task.
	 * @var TaskRunnerInterface|null
	 */
	protected $taskRunner = null;

	public function __construct(TaskRunnerInterface $taskRunner)
	{
		$this->taskRunner = $taskRunner;
	}

	public abstract function run();

}
