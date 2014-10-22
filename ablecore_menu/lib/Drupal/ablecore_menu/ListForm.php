<?php

namespace Drupal\ablecore_menu;

use Drupal\ablecore\Forms\FormBase;

class ListForm extends FormBase {

	public function build($form, &$form_state)
	{
		$relationships = ablecore_menu_get_path_relationships();
		$form['#tree'] = true;

		if (count($relationships) <= 0) {
			$form['#no-results'] = true;
			return $form;
		}

		foreach ($relationships as $id => $relationship) {

			$form['relationships'][$id]['pid'] = array(
				'#type' => 'hidden',
				'#value' => $relationship->get('pid'),
			);
			$form['relationships'][$id]['path'] = array(
				'#markup' => check_plain($relationship->get('path'))
			);

			$menu_link = $relationship->menuLink();
			if (!$menu_link) continue;
			$form['relationships'][$id]['menu_link'] = array(
				'#type' => 'link',
				'#title' => $menu_link['link_title'],
				'#href' => 'admin/structure/menu/item/' . $menu_link['mlid'] . '/edit',
			);
			$form['relationships'][$id]['type'] = array(
				'#markup' => ucfirst(check_plain($relationship->get('type'))),
			);
			$form['relationships'][$id]['edit'] = array(
				'#type' => 'link',
				'#title' => t('edit'),
				'#href' => 'admin/config/ablecore/menu-relationships/' . $relationship->get('pid') . '/edit',
			);
			$form['relationships'][$id]['delete'] = array(
				'#type' => 'link',
				'#title' => t('delete'),
				'#href' => 'admin/config/ablecore/menu-relationships/' . $relationship->get('pid') . '/delete',
			);
			$form['relationships'][$id]['weight'] = array(
				'#type' => 'weight',
				'#title' => t('Weight for @title', array('@title' => $relationship->get('path'))),
				'#title_display' => 'invisible',
				'#default_value' => $relationship->get('weight'),
			);

		}

		$form['actions'] = array('#type' => 'actions');
		$form['actions']['submit'] = array('#type' => 'submit', '#value' => t('Save changes'));

		return $form;
	}

	public function submit($form, &$form_state)
	{
		foreach ($form_state['values']['relationships'] as $id => $data) {
			if (is_array($data) && isset($data['weight']) && isset($data['pid'])) {
				$relationship = MenuPathRelationship::load($data['pid']);
				if ($relationship === false) continue;
				$relationship->set('weight', $data['weight']);
				$relationship->save();
			}
		}
		drupal_set_message(t('The order of the relationships has been saved.'));
	}

}
