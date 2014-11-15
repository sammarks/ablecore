<?php

namespace AbleCore\Schema;

class TableColumn {

	const SIZE_TINY = 'tiny';
	const SIZE_SMALL = 'small';
	const SIZE_MEDIUM = 'medium';
	const SIZE_BIG = 'big';
	const SIZE_NORMAL = 'normal';

	const TYPE_SERIAL = 'serial';
	const TYPE_INTEGER = 'int';
	const TYPE_FLOAT = 'foat';
	const TYPE_NUMERIC = 'numeric';
	const TYPE_VARCHAR = 'varchar';
	const TYPE_CHAR = 'char';
	const TYPE_TEXT = 'text';
	const TYPE_BLOB = 'blob';
	const TYPE_DATETIME = 'datetime';

	/**
	 * The running definition for the column.
	 * @var array
	 */
	protected $definition = array();

	/**
	 * The name of the column to add.
	 * @var string
	 */
	protected $name = '';

	public function __construct($name, $type = self::TYPE_SERIAL, $size = self::SIZE_NORMAL, $unsigned = false, $null = false, $length = false)
	{
		$this->name = $name;
		$this->definition = array(
			'type' => $type,
			'size' => $size,
			'not null' => !$null,
		);

		// Set unsigned if it's a number of some sort.
		if ($type != self::TYPE_VARCHAR && $type != self::TYPE_BLOB && $type != self::TYPE_CHAR
			&& $type != self::TYPE_DATETIME && $type != self::TYPE_TEXT) {
			$this->definition['unsigned'] = $unsigned;
		}

		// Set the default when it's required.
		if (!$null) {
			if (in_array($type, array(self::TYPE_VARCHAR, self::TYPE_CHAR, self::TYPE_TEXT))) {
				$this->definition['default'] = '';
			} elseif (in_array($type, array(self::TYPE_FLOAT, self::TYPE_INTEGER, self::TYPE_NUMERIC))) {
				$this->definition['default'] = 0;
			}
		}

		// Set the length when it's specified.
		if ($length !== false) {
			$this->definition['length'] = $length;
		}
	}

	/**
	 * Sets the length of the column.
	 *
	 * @param int $length The maximum length of the column.
	 *
	 * @return $this
	 */
	public function length($length)
	{
		$this->definition['length'] = $length;
		return $this;
	}

	/**
	 * Sets the description of the column.
	 *
	 * @param string $description The description.
	 *
	 * @return $this
	 */
	public function description($description)
	{
		$this->definition['description'] = $description;
		return $this;
	}

	/**
	 * Declares that the current column is nullable.
	 *
	 * @return $this
	 */
	public function nullable()
	{
		$this->definition['not null'] = false;
		return $this;
	}

	/**
	 * Sets the default value for the column.
	 *
	 * @param mixed $default The default value for the column.
	 *
	 * @return $this
	 */
	public function defaultTo($default)
	{
		$this->definition['default'] = $default;
		return $this;
	}

	/**
	 * Mark the current column as unsigned.
	 *
	 * @return $this
	 */
	public function unsigned()
	{
		$this->definition['unsigned'] = true;
		return $this;
	}

	/**
	 * Sets the size of the column.
	 *
	 * @param string $size The size of the column.
	 *
	 * @return $this
	 */
	public function size($size)
	{
		$this->definition['size'] = $size;
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDefinition()
	{
		return $this->definition;
	}

} 
