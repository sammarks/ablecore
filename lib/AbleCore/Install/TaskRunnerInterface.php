<?php

namespace AbleCore\Install;

interface TaskRunnerInterface {

	/**
	 * @return array An array of tasks to be run. The item is the classname
	 *               of the task to run.
	 */
	public static function getTasks();

}
