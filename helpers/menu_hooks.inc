<?php

function theme_ablecore_menu_tree($variables)
{
	return theme('html_tag', array(
		'element' => array(
			'#tag' => 'ul',
			'#attributes' => $variables['attributes'],
			'#value' => $variables['children'],
		)
	));
}

function ablecore_menu_hooks_theme()
{
	return array(
		'ablecore_menu_tree' => array(
			'variables' => array(
				'children' => '',
				'attributes' => array(),
			)
		)
	);
}
