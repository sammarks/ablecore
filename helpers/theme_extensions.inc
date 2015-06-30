<?php

function ablecore_execute_theme_hook($variables)
{
	// Make sure we have the existing theme hook.
	if (empty($variables['ablecore']['original_configuration']) || !is_array($variables['ablecore']['original_configuration'])) {
		watchdog('ablecore', 'An existing theme hook could not be found.', array(), WATCHDOG_WARNING);
		return '';
	}

	// Make sure the file for the existing theme hook is loaded.
	$existing_theme_hook = $variables['ablecore']['original_configuration'];
	foreach ($existing_theme_hook['includes'] as $include) {
		require_once DRUPAL_ROOT . '/' . $include;
	}

	// Remove the existing ablecore variable.
	unset($variables['ablecore']);

	// Call the existing theme hook.
	$result = call_user_func($existing_theme_hook['function'], $variables);

	// If the result is an array, render it. If not, return the raw value.
	if (is_array($result)) {
		return drupal_render($result);
	} else {
		return $result;
	}
}
