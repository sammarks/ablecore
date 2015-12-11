<?php

function ablecore_helper_themes_theme()
{
	return \AbleCore\Modules\ThemeManager::init()
		->defineFunction('columns', 'element', 'helper_themes/columns.inc', array(
			'variables' => array(
				'num_columns' => 3,
				'items' => array(),
				'container_classes' => array('columns-container'),
				'column_classes' => array('column')
			)
		))
		->fin();
}
