<?php

namespace AbleCore\Plugins;

class PluginConfiguration {

	protected $module;
	protected $namespaces = array();
	protected $suppress_inc = false;

	public function __construct($module)
	{
		$this->module = $module;
	}

	/**
	 * Creates a new instance of the PluginConfiguration class with the specified module.
	 *
	 * @param string $module The name of the module that owns this configuration.
	 *
	 * @return static
	 */
	public static function init($module)
	{
		$instance = new static($module);
		return $instance;
	}

	/**
	 * Associates a plugin type with a specific namespace.
	 *
	 * @param string $type The type of CTools plugin.
	 * @param string $namespace The namespace to associate the CTools plugin with.
	 *
	 * @return $this
	 */
	protected function setPluginTypeNamespace($type, $namespace)
	{
		$this->namespaces[$type] = $namespace;
		return $this;
	}

	/**
	 * Checks the provided namespaces to make sure they actually contain valid <code>.inc</code>
	 * files inside of them.
	 *
	 * @param array $namespaces The namespaces to check.
	 */
	protected function checkNamespaces(array $namespaces)
	{
		foreach ($namespaces as $namespace) {
			$this->checkNamespace($namespace);
		}
	}

	/**
	 * Checks the provided namespace to make sure it actually contains valid <code>.inc</code>
	 * files inside of it. If it does not contain any <code>.inc</code> files inside, it throws
	 * a watchdog warning.
	 *
	 * @param string $namespace
	 */
	protected function checkNamespace($namespace)
	{
		$namespace_folder = ablecore_translate_namespace($this->module, $namespace);
		$inc_files = glob(DRUPAL_ROOT . '/' . $namespace_folder . '/*.inc');
		if (empty($inc_files) && !$this->suppress_inc) {
			watchdog('ablecore', 'The namespace @namespace does not have any <code>.inc</code> files inside it. Classes used as CTools ' .
				'plugins are required to end in <code>.inc</code> instead of <code>.php</code>. You may want to revise your class ' .
				'filenames. <em>Tip: To remove this warning, chain a call to <code>suppressIncWarnings()</code> onto your ' .
				'<code>hook_ablecore_plugin_configuration</code> implementation.</em>', array('@namespace' => $namespace), WATCHDOG_WARNING);
		}
	}

	/**
	 * Exports the configuration to an array that can be stored in the Database.
	 *
	 * @return array
	 */
	public function export()
	{
		$this->checkNamespaces(array_keys($this->namespaces));
		return $this->namespaces;
	}

	#region Configuration Functions

	/**
	 * Suppresses the .inc file warnings generated when the added namespaces are being processed.
	 * @see checkNamespace()
	 *
	 * @return $this
	 */
	public function suppressIncWarnings($suppress = true)
	{
		$this->suppress_inc = $suppress;
		return $this;
	}

	/**
	 * Sets the namespace for the content_type plugin type.
	 *
	 * @param string $namespace
	 *
	 * @return $this
	 */
	public function setContentTypesNamespace($namespace)
	{
		return $this->setPluginTypeNamespace('content_type', $namespace);
	}

	#endregion

}
