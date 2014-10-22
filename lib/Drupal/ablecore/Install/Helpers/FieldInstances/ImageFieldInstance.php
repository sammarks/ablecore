<?php

namespace Drupal\ablecore\Install\Helpers\FieldInstances;

class ImageFieldInstance extends FileFieldInstance {

	public function setDefaults()
	{
		parent::setDefaults();
		$this->setExtensions(self::EXTENSIONS_IMAGE);
	}

	/**
	 * Set Minimum Resolution
	 *
	 * @param int $width  The width.
	 * @param int $height The height.
	 *
	 * @return $this
	 */
	public function setMinResolution($width, $height)
	{
		return $this->setSetting('min_resolution', $width . 'x' . $height);
	}

	/**
	 * Set Maximum Resolution
	 *
	 * @param int $width  The width.
	 * @param int $height The height.
	 *
	 * @return $this
	 */
	public function setMaxResolution($width, $height)
	{
		return $this->setSetting('max_resolution', $width . 'x' . $height);
	}

	/**
	 * Set Default Image
	 *
	 * @param int $fid The FID of the default image.
	 *
	 * @return $this
	 */
	public function setDefaultImage($fid)
	{
		return $this->setSetting('default_image', $fid);
	}

	/**
	 * Enable Alt Field
	 *
	 * @return $this
	 */
	public function enableAltField()
	{
		return $this->setSetting('alt_field', true);
	}

	/**
	 * Disable Alt Field
	 *
	 * @return $this
	 */
	public function disableAltField()
	{
		return $this->setSetting('alt_field', false);
	}

	/**
	 * Enable Title Field
	 *
	 * @return $this
	 */
	public function enableTitleField()
	{
		return $this->setSetting('title_field', true);
	}

	/**
	 * Disable Title Field
	 *
	 * @return $this
	 */
	public function disableTitleField()
	{
		return $this->setSetting('title_field', false);
	}
}
