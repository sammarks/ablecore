<?php

function ablecore_preprocess_node(&$variables)
{
	// Add the view mode template suggestion: node--type--view-mode.tpl.php
	if (array_key_exists('view_mode', $variables) && $variables['view_mode']) {
		$variables['theme_hook_suggestions'][] = 'node__' . str_replace('-', '_', $variables['view_mode']);
		$variables['theme_hook_suggestions'][] = 'node__' . str_replace('-', '_', $variables['node']->type) .
			'__' . str_replace('-', '_', $variables['view_mode']);
	}

	// Add the Able Core node to every node template.
	$variables['ac_node'] = Drupal\ablecore\Node::importFull($variables['node']);
}
