<?php

namespace Drupal\ablecore_menu;
use Drupal\ablecore\Forms\FormBase;

class EditForm extends FormBase {

	public function build($form, &$form_state)
	{
		$form['type'] = array(
			'#type' => 'select',
			'#title' => t('Type'),
			'#options' => array(
				'wildcard' => t('Wildcard'),
				'regex' => t('Regular Expression'),
			),
			'#default_value' => 'wildcard',
		);
		$form['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#description' => t('Depending on the type selected, either a wildcard notation path or a regex statement.'),
			'#default_value' => '',
			'#required' => true,
		);

		// Generate a list of menus.
//		$menus = menu_get_menus();
//		$menu_options = array();
//		foreach ($menus as $machine_name => $name) {
//			$menu_options[$name] = menu_tree_all_data($machine_name);
//		}
		$form['mlid'] = array(
			'#type' => 'select',
			'#title' => t('Menu Link'),
			'#description' => t('The menu link to associate the pattern with.'),
			'#options' => menu_parent_options(menu_get_menus(), array('mlid' => 0)),
			'#required' => true,
		);

		$form['actions'] = array('#type' => 'actions');
		$form['actions']['submit'] = array('#type' => 'submit', '#value' => 'Save');

		$this->loadExisting($form, $form_state);

		return $form;
	}

	protected function loadExisting(&$form, &$form_state)
	{
		if (isset($form_state['build_info']['args'][0]) &&
			is_object($form_state['build_info']['args'][0]) &&
			get_class($form_state['build_info']['args'][0]) == 'Drupal\ablecore_menu\MenuPathRelationship') {

			$relationship = $form_state['build_info']['args'][0];

			// Set the existing relationship.
			$form_state['existing_relationship'] = $relationship;

			// Set values.
			$form['type']['#default_value'] = $relationship->get('type');
			$form['path']['#default_value'] = $relationship->get('path');

			$menu_link = $relationship->menuLink();
			$form['mlid']['#default_value'] = $menu_link['menu_name'] . ':' . $relationship->get('mlid');

			// Add a delete button.
			$form['actions']['delete'] = array(
				'#type' => 'markup',
				'#markup' => l(t('Delete'), 'admin/config/ablecore/menu-relationships/' . $relationship->get('pid') . '/delete'),
			);
		}
	}

	public function submit($form, &$form_state)
	{
		$relationship = new MenuPathRelationship();
		if (array_key_exists('existing_relationship', $form_state)) {
			$relationship = $form_state['existing_relationship'];
		}

		// Get the mlid.
		$mlid_values = explode(':', $form_state['values']['mlid']);
		$mlid = $mlid_values[1];

		$relationship->set('path', $form_state['values']['path']);
		$relationship->set('mlid', $mlid);
		$relationship->set('type', $form_state['values']['type']);

		if ($relationship->save() !== false) {
			drupal_set_message('Relationship saved successfully!');
		} else {
			drupal_set_message('There was an error saving that relationship. Please try again later.', 'error');
		}
		drupal_goto('admin/config/ablecore/menu-relationships');
	}

	public function validate($form, &$form_state)
	{
		// Validate the MLID selection.
		$mlid_values = explode(':', $form_state['values']['mlid']);
		if (count($mlid_values) != 2 || menu_link_load($mlid_values[1]) === false) {
			form_set_error('mlid', t('The menu link provided is invalid.'));
			return;
		}
	}

}
