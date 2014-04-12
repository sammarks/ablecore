<?php

namespace AbleCore\Modules;

class ImageStyleManager {

	private $generated = array();

	public static function init()
	{
		return new self();
	}

	public function define($name, $label, $effects = array())
	{
		$this->generated[$name] = array(
			'label' => $label,
			'effects' => $effects,
		);
		return $this;
	}

	public function defineScale($name, $label, $width = null, $height = null, $upscale = false)
	{
		$effects = array();
		$scale_effect = array(
			'name' => 'image_scale',
			'data' => array(),
		);
		if ($width !== null)
			$scale_effect['data']['width'] = $width;
		if ($height !== null)
			$scale_effect['data']['height'] = $height;
		$scale_effect['data']['upscale'] = $upscale;
		$effects[] = $scale_effect;
		return $this->define($name, $label, $effects);
	}

}
