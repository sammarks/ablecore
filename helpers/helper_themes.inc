<?php

function ablecore_helper_themes_theme()
{
	return \Drupal\ablecore\Modules\ThemeManager::init()
		->defineFunction('columns', 'element', 'helpers/helper_themes.inc', array(
				'variables' => array('num_columns' => 3, 'items' => array())
		))
		->fin();
}

function theme_columns($variables)
{
	$num_columns = $variables['num_columns'];
	$children = $variables['items'];

	$per_column = ceil(count($children) / $num_columns);
	$rendered_columns = array(
		'#type' => 'container',
		'#attributes' => array('class' => array('column-container')),
	);
	for ($i = 0; $i < $num_columns; $i++) {
		$column = array(
			'#theme' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => array('class' => array('column', 'column-' . $i)),
			'#value' => '',
		);
		if (count($children) >= $i + 1) {
			for ($k = 0; $k < $per_column; $k++) {
				if ($i * $per_column + $k > count($children)) break;
				$column['#value'] .= render($children[$i * $per_column + $k]);
			}
		}
		$rendered_columns[] = $column;
	}

	return render($rendered_columns);
}
