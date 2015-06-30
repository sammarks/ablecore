<?php

/**
 * Gets all plugin configurations from modules that implement hook_ablecore_plugin_configuration()
 *
 * @return mixed
 */
function ablecore_get_plugin_configurations()
{
	return ablecore_cache_wrapper('ablecore_plugin_configurations', function () {

		$configurations = array();
		foreach (module_implements('ablecore_plugin_configuration') as $module) {

			$plugin_configuration = call_user_func($module);
			if (!$plugin_configuration) {
				continue;
			}
			if (!($plugin_configuration instanceof \AbleCore\Plugins\PluginConfiguration)) {
				throw new \Exception('Invalid plugin configuration returned.');
			}

			$configurations[$module] = $plugin_configuration->export();

		}

		drupal_alter('ablecore_plugin_configuration', $configurations);

	});
}
