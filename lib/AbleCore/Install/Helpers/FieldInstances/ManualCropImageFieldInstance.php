<?php
/**
 * @file ManualCropImageFieldInstance.php
 */
namespace AbleCore\Install\Helpers\FieldInstances;

class ManualCropImageFieldInstance extends ImageFieldInstance {

	const LIST_MODE_EXCLUDE = 'exclude';
	const LIST_MODE_INCLUDE = 'include';

	public function setDefaults()
	{
		parent::setDefaults();

		$this->setWidgetSetting('manualcrop_enable', true);
		$this->enableThumbnailList();
		$this->setStylesListMode(self::LIST_MODE_INCLUDE);
		return $this;
	}

	/**
	 * Enable Thumbnail List
	 *
	 * This disables showing a button or a selection list and instead enables
	 * showing all thumbnails.
	 *
	 * @return $this
	 */
	public function enableThumbnailList()
	{
		return $this->setWidgetSetting('manualcrop_thumblist', true);
	}

	/**
	 * Disable Thumbnail List
	 *
	 * This disables showing all thumbnails and instead shows a button or a
	 * selection list.
	 *
	 * @return $this
	 */
	public function disableThumbnailList()
	{
		return $this->setWidgetSetting('manualcrop_thumblist', false);
	}

	/**
	 * Enable Inline Cropping
	 *
	 * This enables instead of opening an overlay, using inline cropping.
	 *
	 * @return $this
	 */
	public function enableInlineCropping()
	{
		return $this->setWidgetSetting('manualcrop_inline_crop', true);
	}

	/**
	 * Disable Inline Cropping
	 *
	 * This disables instead of opening an overlay, using inline cropping.
	 *
	 * @return $this
	 */
	public function disableInlineCropping()
	{
		return $this->setWidgetSetting('manualcrop_inline_crop', false);
	}

	/**
	 * Enable Show Crop Info
	 *
	 * This enables showing the crop selection details.
	 *
	 * @return $this
	 */
	public function enableShowCropInfo()
	{
		return $this->setWidgetSetting('manualcrop_crop_info', true);
	}

	/**
	 * Disable Show Crop Info
	 *
	 * This disables showing the crop selection details.
	 *
	 * @return $this
	 */
	public function disableShowCropInfo()
	{
		return $this->setWidgetSetting('manualcrop_crop_info', false);
	}

	/**
	 * Enable Instant Preview
	 *
	 * This enables showing an instant preview of the crop selection.
	 *
	 * @return $this
	 */
	public function enableInstantPreview()
	{
		return $this->setWidgetSetting('manualcrop_instant_preview', true);
	}

	/**
	 * Disable Instant Preview
	 *
	 * This disables showing an instant preview of the crop selection.
	 *
	 * @return $this
	 */
	public function disableInstantPreview()
	{
		return $this->setWidgetSetting('manualcrop_instant_preview', false);
	}

	/**
	 * Enable Crop After Upload
	 *
	 * This enables opening the cropping tool directly after the file upload. Note
	 * this will only work if you enable only one image style.
	 *
	 * @return $this
	 */
	public function enableCropAfterUpload()
	{
		return $this->setWidgetSetting('manualcrop_instant_crop', true);
	}

	/**
	 * Disable Crop After Upload
	 *
	 * This disables opening the cropping tool directly after the file upload. Note
	 * this will only work if you enable only one image style.
	 *
	 * @return $this
	 */
	public function disableCropAfterUpload()
	{
		return $this->setWidgetSetting('manualcrop_instant_crop', false);
	}

	/**
	 * Enable Default Crop Area
	 *
	 * This enables creating a default crop area when opening the croptool for
	 * uncropped images.
	 *
	 * @return $this
	 */
	public function enableDefaultCropArea()
	{
		return $this->setWidgetSetting('manualcrop_default_crop_area', true);
	}

	/**
	 * Disable Default Crop Area
	 *
	 * This disables creating a default crop area when opening the croptool for
	 * uncropped images.
	 *
	 * @return $this
	 */
	public function disableDefaultCropArea()
	{
		return $this->setWidgetSetting('manualcrop_default_crop_area', false);
	}

	/**
	 * Set Styles List Mode
	 *
	 * @param string $list_mode The list mode. Either include or exclude.
	 *
	 * @return $this
	 */
	public function setStylesListMode($list_mode = self::LIST_MODE_INCLUDE)
	{
		return $this->setWidgetSetting('manualcrop_styles_mode', $list_mode);
	}

	/**
	 * Set Styles List
	 *
	 * @param array $styles   A 1-dimensional array containing a list of styles to include.
	 * @param bool  $required Whether or not to make the specified styles required.
	 *
	 * @return $this
	 */
	public function setStylesList(array $styles, $required = false)
	{
		$this->setWidgetSetting('manualcrop_styles_list', $this->prepareStylesArray($styles));
		if ($required) {
			$this->setRequiredStyles($styles);
		}
		return $this;
	}

	/**
	 * Set Required Styles
	 *
	 * @param array $styles A 1-dimensional array containing a list of required styles.
	 *
	 * @return $this
	 */
	public function setRequiredStyles(array $styles)
	{
		return $this->setWidgetSetting('manualcrop_require_cropping', $this->prepareStylesArray($styles));
	}

	/**
	 * Prepare Styles Array
	 *
	 * @param array $styles The raw array of styles.
	 *
	 * @return array
	 */
	protected function prepareStylesArray(array $styles)
	{
		$new_styles = array();
		foreach ($styles as $style) {
			$new_styles[$style] = $style;
		}

		return $new_styles;
	}

} 
