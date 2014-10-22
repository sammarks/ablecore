<?php

namespace Drupal\ablecore\HookHelpers;

class ImageDefaultStyles {

	protected $styles = array();

	/**
	 * Init
	 *
	 * @return ImageDefaultStyles A new instance of the ImageStyleManager.
	 */
	public static function init()
	{
		return new self();
	}

	/**
	 * Define
	 *
	 * @param string $name    The machine name of the image style.
	 * @param string $label   The human-readable label of the image style.
	 * @param array  $effects An array of effects to attach to the style.
	 *
	 * @return ImageStyle The generated image style.
	 */
	public function define($name, $label, $effects = array())
	{
		$style = new ImageStyle($name, $label, $effects);
		$this->styles[] = $style;
		return $style;
	}

	/**
	 * Finish
	 *
	 * @return array The final result, ready to pass to hook_image_default_styles()
	 */
	public function fin()
	{
		$generated = array();
		foreach ($this->styles as $style) {
			/** @var ImageStyle $style */
			$generated[$style->getName()] = $style->getDefinition();
		}

		return $generated;
	}

}
