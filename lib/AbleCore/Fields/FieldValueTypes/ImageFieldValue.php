<?php

namespace AbleCore\Fields\FieldValueTypes;

use AbleCore\Fields\FieldValueTypes\FileFieldValue;

class ImageFieldValue extends FileFieldValue
{
	/**
	 * The width of the image in pixels.
	 * @var int
	 */
	public $width;

	/**
	 * The height of the image in pixels.
	 * @var int
	 */
	public $height;

	/**
	 * The alt text for the image.
	 * @var string
	 */
	public $alt;

	/**
	 * The title of the image.
	 * @var string
	 */
	public $title;

	public function __construct($raw, $value, $type)
	{
		parent::__construct($raw, $value, $type);

		if (array_key_exists('width', $raw))
			$this->width = $raw['width'];
		if (array_key_exists('height', $raw))
			$this->height = $raw['height'];
		if (array_key_exists('alt', $raw))
			$this->alt = $raw['alt'];
		if (array_key_exists('title', $raw)) {
			$this->title = $raw['title'];
			$this->description = $raw['title'];
		}
	}

	/**
	 * Gets the image style version of the image.
	 *
	 * @param string $image_style The image style to use.
	 *
	 * @return string
	 */
	public function style($image_style)
	{
		return image_style_url($image_style, $this->uri);
	}
}
