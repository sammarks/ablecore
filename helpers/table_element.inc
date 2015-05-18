<?php

function form_type_table_process($element)
{
	foreach ($element['#rows'] as $index => $row) {
		$element[$index] = array();
		foreach ($row as $row_item_index => $row_item) {
			if (!is_array($row_item)) {
				$element['#rows'][$index][$row_item_index] = $row_item;
			} else {
				$element[$index][$row_item_index] = &$row_item;
				$element['#rows'][$index][$row_item_index] = array('data' => &$row_item);
			}
			unset($row_item);
		}
	}

	return $element;
}
