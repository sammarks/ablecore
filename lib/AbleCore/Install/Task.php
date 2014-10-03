<?php

namespace AbleCore\Install;

interface TaskInterface {

	public function __construct(TaskRunnerInterface $task_runner);

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
	protected $task_runner = null;

	public function __construct(TaskRunnerInterface $task_runner)
	{
		$this->task_runner = $task_runner;
	}

	public abstract function run();

}
