<?php

namespace AbleCore\Install\Helpers\FieldInstances;

use AbleCore\Install\Helpers\FieldInstance;

class FileFieldInstance extends FieldInstance {

	const EXTENSIONS_IMAGE = 'png gif jpg jpeg';
	const EXTENSIONS_DOCUMENT = 'doc docx txt pdf md rtf';

	public function setDefaults()
	{
		$this->setExtensions(self::EXTENSIONS_DOCUMENT);
		$this->setDirectory(str_replace('_', '-', $this->field->getName()));
	}

	/**
	 * Set Directory
	 *
	 * @param string $directory The directory to upload files to.
	 *
	 * @return $this
	 */
	public function setDirectory($directory)
	{
		return $this->setSetting('file_directory', $directory);
	}

	/**
	 * Set Extensions
	 *
	 * @param string $extensions A string of extensions this field supports.
	 *
	 * @return $this
	 */
	public function setExtensions($extensions = self::EXTENSIONS_IMAGE)
	{
		return $this->setSetting('file_extensions', $extensions);
	}

	/**
	 * Set Max Filesize
	 *
	 * @param int $max The maximum filesize (in bytes) for this field.
	 *
	 * @return $this
	 */
	public function setMaxFilesize($max)
	{
		return $this->setSetting('max_filesize', $max);
	}

	/**
	 * Enable Description Field
	 *
	 * @return $this
	 */
	public function enableDescriptionField()
	{
		return $this->setSetting('description_field', true);
	}

	/**
	 * Disable Description Field
	 *
	 * @return $this
	 */
	public function disableDescriptionField()
	{
		return $this->setSetting('description_field', false);
	}

}
