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
		$taskRunner = $this->getTaskRunnerClass();
		$tasks = $taskRunner->getTasks();
		foreach ($tasks as $task) {
			$taskClass = $this->getTaskClass($task, $taskRunner);
			$result = $this->runTask($taskClass);
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
	 * @param string              $taskName   The name of the task to execute.
	 * @param TaskRunnerInterface $taskRunner The current task runner.
	 *
	 * @return Task The task.
	 * @throws \Exception
	 */
	protected function getTaskClass($taskName, TaskRunnerInterface $taskRunner)
	{
		$class = $this->getInstallerNamespacePrefix() . $taskName;
		if (class_exists($class)) {
			if ($class instanceof Task) {
				return new $class($taskRunner);
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
			if ($class instanceof TaskRunnerInterface) {
				return new $class();
			} else {
				throw new \Exception('The TaskRunner class for the module ' . $this->module . ' is invalid.');
			}
		} else {
			throw new \Exception('The TaskRunner class for the module ' . $this->module . ' does not exist.');
		}
	}

} 
