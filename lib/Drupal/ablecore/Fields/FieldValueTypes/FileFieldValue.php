<?php

namespace Drupal\ablecore\Fields\FieldValueTypes;
use Drupal\ablecore\Fields\FieldValue;

class FileFieldValue extends FieldValue
{
	/**
	 * The Drupal internal URI.
	 * @var string
	 */
	public $uri;

	/**
	 * The absolute URL.
	 * @var string
	 */
	public $url;

	/**
	 * The description, if available.
	 * @var string
	 */
	public $description;

	/**
	 * The size in bytes.
	 * @var int
	 */
	public $size;

	/**
	 * The filename (with extension).
	 * @var string
	 */
	public $name;

	/**
	 * The mimetype.
	 * @var string
	 */
	public $mime;

	/**
	 * Whether the file is enabled.
	 * @var bool
	 */
	public $status = false;

	/**
	 * The file extension.
	 * @var string
	 */
	public $extension;

	/**
	 * Timestamp for when the file was uploaded.
	 * @var int
	 */
	public $timestamp;

	/**
	 * The ID of the user who uploaded the file.
	 * @var int
	 */
	public $uid;

	/**
	 * The ID of the file.
	 * @var int
	 */
	public $fid;

	public function __construct($raw, $value, $type)
	{
		parent::__construct($raw, $value, $type);

		if (array_key_exists('description', $raw))
			$this->description = $raw['description'];
		if (array_key_exists('filesize', $raw))
			$this->size = $raw['filesize'];
		if (array_key_exists('filemime', $raw))
			$this->mime = $raw['filemime'];
		if (array_key_exists('status', $raw))
			$this->status = ($raw['status']);
		if (array_key_exists('timestamp', $raw))
			$this->timestamp = $raw['timestamp'];
		if (array_key_exists('uid', $raw))
			$this->uid = $raw['uid'];
		if (array_key_exists('fid', $raw))
			$this->fid = $raw['fid'];

		if (array_key_exists('filename', $raw)) {
			$this->name = $raw['filename'];
			$this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
		}

		if (array_key_exists('uri', $raw)) {
			$this->uri = $raw['uri'];
			$this->url = file_create_url($raw['uri']);
		}
	}

	/**
	 * Gets the internal Drupal file object.
	 * @return mixed
	 */
	public function file()
	{
		return file_load($this->fid);
	}

	/**
	 * Loads the user that uploaded the file.
	 * @return mixed
	 */
	public function user()
	{
		return user_load($this->uid);
	}

	/**
	 * Gets the human-readable size of the file.
	 * @return string
	 */
	public function humanSize()
	{
		return format_size($this->size);
	}
}
