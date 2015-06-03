<?php

function ablecore_columns_pre_render($element)
{
	$num_columns = $element['#num_columns'];

	// Map the children if there is an items array and it's populated.
	if (array_key_exists('#items', $element) && !empty($element['#items']) && is_array($element['#items'])) {
		foreach ($element['#items'] as $item) {
			if (!is_array($item)) {
				$element[] = array(
					'#type' => 'html_tag',
					'#tag' => 'li',
					'#value' => $item,
				);
			} elseif (array_key_exists('#children', $item)) {
				$element[] = array('#markup' => $item['#children']);
			} else {
				$element[] = $item;
			}
		}
		unset($element['#items']);
	}
	$children_keys = element_children($element);

	$per_column = ceil(count($children_keys) / $num_columns);
	$element['#attributes'] = array('class' => $element['#container_classes']);
	for ($i = 0; $i < $num_columns; $i++) {
		$column = array(
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => array('class' => array_merge($element['#column_classes'], array('column-' . $i))),
		);
		if (count($children_keys) >= $i + 1) {
			for ($k = 0; $k < $per_column; $k++) {
				if ($i * $per_column + $k >= count($children_keys)) {
					break;
				}
				$column[] = $element[$children_keys[$i * $per_column + $k]];
				unset($element[$children_keys[$i * $per_column + $k]]);
			}
		}
		$element[] = $column;
	}

	return $element;
}
