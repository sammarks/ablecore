<?php

/**
 * Implements hook_theme_registry_alter()
 */
function ablecore_theme_registry_alter(&$registry)
{
	// Search through the registry and update module paths.
	foreach ($registry as $key => $item) {

		// Narrow down our results.
		if (!array_key_exists('variables', $item) || !array_key_exists('ablecore', $item['variables'])
			|| !is_array($item['variables']['ablecore']) || !array_key_exists('module', $item['variables']['ablecore'])
		) continue;

		// Delete the identifying ablecore variable.
		$module = $registry[$key]['variables']['ablecore']['module'];
		unset($registry[$key]['variables']['ablecore']);

		// Clear the variables array if no more exist.
		if (count($registry[$key]['variables']) <= 0) {
			unset($registry[$key]['variables']);
		}

		// Call the respective helper functions.
		_ablecore_theme_registry_alter_process_helper_files($registry, $key, $item, $module);
		_ablecore_theme_registry_alter_render_array_support($registry, $key, $item, $module);

	}
}

/**
 * Allows theme hooks created under Able Core to return render arrays.
 *
 * @param $registry
 * @param $key
 * @param $item
 * @param $module
 */
function _ablecore_theme_registry_alter_render_array_support(&$registry, $key, $item, $module)
{
	if (!array_key_exists('function', $item)) return;

	$item['variables']['ablecore']['original_configuration'] = $item;
	$item['function'] = 'ablecore_execute_theme_hook';
	$item['file'] = 'helpers/theme_extensions.inc';
	$item['theme path'] = drupal_get_path('module', 'ablecore');
	$item['includes'] = array(drupal_get_path('module', 'ablecore') . '/' . $item['file']);

	$registry[$key] = $item;
}

/**
 * Adds support for preprocess includes for each Able Core theme hook.
 *
 * @param $registry
 * @param $key
 * @param $item
 * @param $module
 */
function _ablecore_theme_registry_alter_process_helper_files(&$registry, $key, $item, $module)
{
	// Narrow down the results further.
	if ($item['type'] != 'module' && $item['type'] != 'theme_engine') return;
	if (!array_key_exists('template', $item)) return;

	// Set some base variables.
	$path = str_replace('//', '/', DRUPAL_ROOT . '/' . $item['template']);
	$segments = explode('/', $path);
	$filename = array_pop($segments);

	// Get the module path.
	$module_path = drupal_get_path('module', $module);

	// Prepare preprocessor paths.
	$preprocess_filename = '/' . preg_quote($filename . '.php') . '/';
	$preprocess_path = null;
	$results = file_scan_directory(DRUPAL_ROOT . '/' . $module_path, $preprocess_filename);
	foreach ($results as $file) {
		$preprocess_path = $file->uri;
		break;
	}

	// Include the preprocessor file.
	if ($preprocess_path !== null) {
		$registry[$key]['includes'][] = str_replace(DRUPAL_ROOT . '/', '', $preprocess_path);
		$registry[$key]['preprocess functions'][] = 'preprocess_' . str_replace('-', '_', $filename);
	}
}
