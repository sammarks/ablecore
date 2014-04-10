<?php

namespace Drupal\ablecore_menu;
use AbleCore\Forms\FormBase;

class DeleteForm extends FormBase {

	public function build($form, &$form_state)
	{
		if (!isset($form_state['build_info']['args'][0]) ||
			!is_object($form_state['build_info']['args'][0]) ||
			get_class($form_state['build_info']['args'][0]) != 'Drupal\ablecore_menu\MenuPathRelationship') {
			drupal_not_found();
		}

		$relationship = $form_state['build_info']['args'][0];

		$menu_link = $relationship->menuLink();
		$form['message'] = array(
			'#type' => 'markup',
			'#markup' => t('Are you sure you want to delete the relationship between <code>@path</code> and <strong>@linktitle?</strong>', array(
				'@path' => $relationship->get('path'),
				'@linktitle' => $menu_link['link_title'],
			)),
		);
		$form['actions'] = array('#type' => 'actions');
		$form['actions']['delete'] = array(
			'#type' => 'submit',
			'#value' => 'Delete',
		);
		$form['actions']['cancel'] = array(
			'#type' => 'markup',
			'#markup' => l(t('Cancel'), 'admin/config/ablecore/menu-relationships'),
		);

		return $form;
	}

	public function submit($form, &$form_state)
	{
		if (!isset($form_state['build_info']['args'][0]) ||
			!is_object($form_state['build_info']['args'][0]) ||
			get_class($form_state['build_info']['args'][0]) != 'Drupal\ablecore_menu\MenuPathRelationship') {
			drupal_not_found();
		}

		$relationship = $form_state['build_info']['args'][0];
		$relationship->delete();

		drupal_set_message('Relationship deleted successfully!');
		drupal_goto('admin/config/ablecore/menu-relationships');
	}

}
