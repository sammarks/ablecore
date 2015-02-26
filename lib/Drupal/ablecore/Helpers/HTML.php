<?php

namespace Drupal\ablecore\Helpers;

class HTML extends Helper {

	public function image($source, $alt = '', $title = '', array $attributes = array(), array $options = array())
	{
		$default_options = array(
			'type' => 'theme',
		);
		$options = array_replace_recursive($default_options, $options);

		$variables = array();
		switch($options['type']) {
			case 'theme':
				global $theme;
				$variables['path'] = drupal_get_path('theme', $theme) . '/' . $source;
				break;
			default:
				$variables['path'] = $source;
		}
		if ($alt) {
			$variables['alt'] = $alt;
		}
		if ($title) {
			$variables['title'] = $title;
		}
		if (count($attributes) > 0) {
			if (array_key_exists('width', $attributes)) {
				$variables['width'] = $attributes['width'];
				unset($attributes['width']);
			}
			if (array_key_exists('height', $attributes)) {
				$variables['height'] = $attributes['height'];
				unset($attributes['height']);
			}
			$variables['attributes'] = $attributes;
		}

		return theme('image', $variables);
	}

	public function absImage($source, $alt = '', $title = '', array $attributes = array(), array $options = array())
	{
		$options['type'] = 'absolute';
		return $this->image($source, $alt, $title, $attributes, $options);
	}

	/**
	 * A wrapper for drupal_render to allow passing non-variable arguments (because the
	 * regular drupal_render is pass-by-reference).
	 *
	 * @param array $elements The elements to render.
	 *
	 * @return string The rendered HTML.
	 * @see drupal_render()
	 */
	public static function render($elements)
	{
		return drupal_render($elements);
	}

} 
