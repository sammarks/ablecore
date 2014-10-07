<?php
/**
 * @file ImageStyle.php
 */
namespace AbleCore\Modules;

class ImageStyle {

	protected $name = '';
	protected $label = '';
	protected $effects = array();

	public function __construct($name, $label, $effects = array())
	{
		$this->name = $name;
		$this->label = $label;
		$this->effects = $effects;
	}

	/**
	 * Add Effect
	 *
	 * @param array $effect The standard effect to add to the image style.
	 *
	 * @return $this
	 */
	public function addEffect(array $effect)
	{
		$this->effects[] = $effect;
		return $this;
	}

	/**
	 * Add Scale Effect
	 *
	 * @param int  $width   The width to scale to (optional).
	 * @param int  $height  The height to scale to (optional).
	 * @param bool $upscale Whether or not to upscale the image.
	 *
	 * @return $this
	 */
	public function addScaleEffect($width = null, $height = null, $upscale = false)
	{
		$effect = array(
			'name' => 'image_scale',
			'data' => array(
				'upscale' => $upscale,
			),
		);
		if ($width !== null)
			$effect['data']['width'] = $width;
		if ($height !== null)
			$effect['data']['height'] = $height;

		return $this->addEffect($effect);
	}

	/**
	 * Add Manual Crop and Scale Effect (ManualCrop)
	 *
	 * @param int  $width              The width for the crop area.
	 * @param int  $height             The height for the crop area.
	 * @param bool $upscale            If true, let scale make images larger than their actual size.
	 * @param bool $respect_minimum    If true, make sure the selected crop area is at least as big as
	 *                                 the destination size. This doesn't enforce minimum image dimensions.
	 * @param bool $only_scale_if_crop If true, only scale the image if it was manually cropped.
	 *
	 * @return ImageStyle
	 */
	public function addManualCropAndScaleEffect($width, $height, $upscale = false, $respect_minimum = true, $only_scale_if_crop = false)
	{
		$effect = array(
			'name' => 'manualcrop_crop_and_scale',
			'data' => array(
				'upscale' => $upscale,
				'respectminimum' => $respect_minimum,
				'onlyscaleifcrop' => $only_scale_if_crop,
				'width' => $width,
				'height' => $height,
			)
		);

		return $this->addEffect($effect);
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get Definition
	 *
	 * @return array The built image style definition.
	 */
	public function getDefinition()
	{
		return array(
			'label' => $this->label,
			'effects' => $this->effects,
		);
	}

} 
