<?php

use Drupal\ablecore_menu\MenuPathRelationship;

function action_list()
{
	return drupal_get_form('ablecore_menu_list_relationships');
}

function action_create()
{
	return drupal_get_form('ablecore_menu_edit_relationship');
}

function action_edit($identifier)
{
	// Load the item.
	$relationship = MenuPathRelationship::load($identifier);
	if ($relationship === false) {
		drupal_not_found();
		return '';
	}

	// Prepare the form.
	return drupal_get_form('ablecore_menu_edit_relationship', $relationship);
}

function action_delete($identifier)
{
	// Load the item.
	$relationship = MenuPathRelationship::load($identifier);
	if ($relationship === false) {
		drupal_not_found();
		return '';
	}

	// Prepare the form.
	return drupal_get_form('ablecore_menu_delete_relationship', $relationship);
}
