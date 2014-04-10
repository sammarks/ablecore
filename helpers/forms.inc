<?php

/**
 * ablecore_forms_get_instance()
 *
 * Gets a specific form instance.
 *
 * @param string $form_id The ID of the instance to get.
 *
 * @return AbleCore\Forms\FormBase|bool FALSE if no instance was found,
 *                                      AbleCore\Forms\FormBase if it was.
 * @throws Exception
 */
function ablecore_forms_get_instance($form_id)
{
	$instances = ablecore_forms_get_instances();
	if (array_key_exists($form_id, $instances)) {
		$instance = $instances[$form_id];
		if (ablecore_forms_validate_instance($instance)) {
			return $instance;
		} else {
			throw new Exception('The form instance for the form ' . $form_id . ' is invalid.');
		}
	}

	return false;
}

/**
 * ablecore_forms_get_instance()
 *
 * Invokes hook_ablecore_form_info(), combines the result and returns it.
 *
 * @return array An array of form instances, keyed by their form IDs.
 */
function ablecore_forms_get_instances()
{
	$instances = &drupal_static(__FUNCTION__);
	if (!isset($instances)) {

		$instances = array();
		foreach (module_implements('ablecore_form_info') as $module) {
			$instances = array_merge($instances, module_invoke($module, 'ablecore_form_info'));
		}

	}
	return $instances;
}

/**
 * ablecore_forms_validate_instance()
 *
 * Validates a form instance.
 *
 * @param object $instance The instance to validate.
 *
 * @return bool TRUE if the instance is valid. FALSE if it is not.
 */
function ablecore_forms_validate_instance($instance)
{
	if (!is_subclass_of($instance, 'AbleCore\Forms\FormBase')) {
		return false;
	}

	return true;
}

function ablecore_forms_forms($form_id, $args)
{
	// Get the form instance.
	$instance = ablecore_forms_get_instance($form_id);
	if ($instance === false) {
		return array();
	}

	return array(
		$form_id => array(
			'callback' => 'ablecore_base_form',
		),
	);
}

function ablecore_base_form($form, &$form_state)
{
	return ablecore_base_form_handle_callback($form, $form_state);
}

function ablecore_base_form_submit($form, &$form_state)
{
	ablecore_base_form_handle_callback($form, $form_state, 'submit');
}

function ablecore_base_form_validate($form, &$form_state)
{
	ablecore_base_form_handle_callback($form, $form_state, 'validate');
}

function ablecore_base_form_handle_callback($form, &$form_state, $type = 'build')
{
	$form_id = $form_state['build_info']['form_id'];
	$instance = ablecore_forms_get_instance($form_id);
	if ($instance === false) return array();

	// Call the builder function on the instance and return the form.
	switch ($type) {
		case 'build':
			return $instance->build($form, $form_state);
		case 'submit':
			return $instance->submit($form, $form_state);
		case 'validate':
			return $instance->validate($form, $form_state);
		default:
			return null;
	}
}
