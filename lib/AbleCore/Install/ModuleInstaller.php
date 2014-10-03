<?php
/**
 * @file ModuleInstaller.php
 */
namespace AbleCore\Install;

class ModuleInstaller {

	/**
	 * The name of the current module being installed.
	 * @var string
	 */
	protected $module = null;

	/**
	 * @param string $module The name of the module being installed.
	 */
	public function __construct($module)
	{
		$this->module = $module;
	}

	/**
	 * Check for Task Runner Class
	 *
	 * @return bool Whether or not the task runner class exists.
	 */
	public function checkForTaskRunnerClass()
	{
		return class_exists($this->getInstallerNamespacePrefix() . 'TaskRunner', true);
	}

	/**
	 * Install
	 *
	 * Installs the module.
	 *
	 * @throws \Exception
	 */
	public function install()
	{
		$task_runner = $this->getTaskRunnerClass();
		$tasks = $task_runner->getTasks();
		foreach ($tasks as $task) {
			$task_class = $this->getTaskClass($task, $task_runner);
			$result = $this->runTask($task_class);
			if ($result === false) {
				watchdog('ablecore', 'The installation operation failed on the task !task for the module !module', array(
					'!task' => $task,
					'!module' => $this->module,
				), WATCHDOG_ALERT);
				break;
			}
		}
	}

	/**
	 * Get Task Class
	 *
	 * @param string              $task_name   The name of the task to execute.
	 * @param TaskRunnerInterface $task_runner The current task runner.
	 *
	 * @return Task The task.
	 * @throws \Exception
	 */
	protected function getTaskClass($task_name, TaskRunnerInterface $task_runner)
	{
		$class = $this->getInstallerNamespacePrefix() . $task_name;
		if (class_exists($class)) {
			if (is_subclass_of($class, '\\AbleCore\\Install\\Task')) {
				return new $class($task_runner);
			} else {
				throw new \Exception('The Task ' . $class . ' for the module ' . $this->module . ' is invalid.');
			}
		} else {
			throw new \Exception('The Task ' . $class . ' for the module ' . $this->module . ' does not exist.');
		}
	}

	/**
	 * Run Task
	 *
	 * @param Task $task The task to run.
	 *
	 * @return bool Whether or not the task was successful.
	 */
	protected function runTask(Task $task)
	{
		return $task->run();
	}

	/**
	 * Get Installer Namespace Prefix
	 *
	 * @return string The prefix for the installer namespace for the current module.
	 */
	protected function getInstallerNamespacePrefix()
	{
		return '\\Drupal\\' . $this->module . '\\Install\\';
	}

	/**
	 * Get Task Runner Class
	 *
	 * @return TaskRunnerInterface The task runner class.
	 * @throws \Exception
	 */
	protected function getTaskRunnerClass()
	{
		$prefix = $this->getInstallerNamespacePrefix();
		if (class_exists($prefix . 'TaskRunner', true)) {
			$class = $prefix . 'TaskRunner';
			if (is_subclass_of($class, '\\AbleCore\\Install\\TaskRunnerInterface')) {
				return new $class();
			} else {
				throw new \Exception('The TaskRunner class for the module ' . $this->module . ' is invalid.');
			}
		} else {
			throw new \Exception('The TaskRunner class for the module ' . $this->module . ' does not exist.');
		}
	}

} 
