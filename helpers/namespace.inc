<?php

/**
 * Given a module and a namespace, gets the file path (relative to the Drupal
 * root) to the namespace's folder.
 *
 * @param string $module The name of the module.
 * @param string $namespace The namespace.
 *
 * @return string
 */
function ablecore_translate_namespace($module, $namespace)
{
	$module_library_path = drupal_get_path('module', $module) . '/lib/';
	$namespace_folder = $module_library_path . str_replace('\\', '/', $namespace);

	return $namespace_folder;
}
