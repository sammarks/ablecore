<?php

/**
 * hook_ablecore_form_info
 *
 * Allows for modules to provide information about the form classes they implement.
 * Form classes must extend the base AbleCore\Forms\Form class, otherwise they will
 * not be loaded.
 *
 * The form info is cached on each page request, so you'll have to do a drupal_static_reset
 * if you want to alter form information during a page request.
 *
 * This function returns an array of form instances, keyed by their form IDs.
 *
 * NOTE: You do NOT have to implement hook_forms as any forms registered with this hook are
 * added in the ablecore module.
 *
 * @return array The form instances, keyed by their IDs.
 */
function hook_ablecore_form_info()
{
	$forms = array();
	$forms['test_form_id'] = new FormClass();
	return $forms;
}
