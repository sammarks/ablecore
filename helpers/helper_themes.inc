<?php

function ablecore_helper_themes_theme()
{
	return \AbleCore\Modules\ThemeManager::init()
		->defineFunction('columns', 'element', 'helpers/helper_themes.inc', array(
			'variables' => array(
				'num_columns' => 3,
				'items' => array(),
				'container_classes' => array('columns-container'),
				'column_classes' => array('column')
			)
		))
		->fin();
}

function theme_columns($variables)
{
	$num_columns = $variables['num_columns'];
	$children = $variables['items'];
	$container_classes = $variables['container_classes'];
	$column_classes = $variables['column_classes'];

	$render = array(
		'#type' => 'columns',
		'#items' => $children,
		'#num_columns' => $num_columns,
		'#container_classes' => $container_classes,
		'#column_classes' => $column_classes,
	);

	return drupal_render($render);
}
