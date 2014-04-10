<?php

function theme_ablecore_menu_list_relationships($variables)
{
	$form = $variables['form'];

	if (array_key_exists('#no-results', $form) && $form['#no-results'] == true) {
		return '<p>There are currently no relationships available. Why not create one?</p>';
	}

	$rows = array();
	foreach (element_children($form['relationships']) as $id) {
		$form['relationships'][$id]['weight']['#attributes']['class'] = array('relationships-order-weight');
		$rows[] = array(
			'data' => array(
				drupal_render($form['relationships'][$id]['path']),
				drupal_render($form['relationships'][$id]['pid']),
				drupal_render($form['relationships'][$id]['menu_link']),
				drupal_render($form['relationships'][$id]['type']),
				drupal_render($form['relationships'][$id]['weight']),
				drupal_render($form['relationships'][$id]['edit']),
				drupal_render($form['relationships'][$id]['delete']),
			),
			'class' => array('draggable'),
		);
	}

	$header = array(
		array(
			'data' => t('Path'),
			'colspan' => 2,
		),
		t('Menu Link'),
		t('Type'),
		t('Weight'),
		array(
			'data' => t('Operations'),
			'colspan' => 2,
		)
	);

	$output = theme('table', array(
		'header' => $header,
		'rows' => $rows,
		'attributes' => array(
			'id' => 'relationships-listing',
		),
	));
	$output .= drupal_render_children($form);

	drupal_add_tabledrag('relationships-listing', 'order', 'subling', 'relationships-order-weight');

	return $output;
}
