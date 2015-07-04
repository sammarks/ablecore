<?php

/**
 * Implements hook_ctools_plugin_directory()
 */
function ablecore_ctools_plugin_directory($module, $plugin)
{
	$configurations = ablecore_get_plugin_configurations();
	if (array_key_exists($module, $configurations) && array_key_exists($plugin, $configurations[$module])) {
		return $configurations[$module][$plugin];
	}

	return array();
}
