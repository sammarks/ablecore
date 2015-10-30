<?php

function ablecore_preprocess_html_tag(&$variables)
{
	// Normally, the HTML tag element only supports standard HTML in the #value
	// key, so we couldn't nest child elements into the HTML tag parent element.
	// This preprocess function basically loops through all the children of the
	// HTML tag, renders them, and adds them to the #value item as a string so
	// that they are rendered correctly later. This functionality should not be
	// intrusive, since it doesn't really mess up anything that is already there,
	// so we should be able to keep it.

	foreach (element_children($variables['element']) as $key) {
		if (empty($variables['element']['#value'])) {
			$variables['element']['#value'] = '';
		}
		$variables['element']['#value'] .= drupal_render($variables['element'][$key]);
	}
}
