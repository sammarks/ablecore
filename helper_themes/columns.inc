<?php

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
