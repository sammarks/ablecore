<?php

namespace AbleCore\Schema;

class TableDefinition {

	/**
	 * The running schema definition.
	 * @var array
	 */
	protected $definition = array();

	/**
	 * The name of the table to create.
	 * @var string
	 */
	protected $name = '';

	/**
	 * A running list of columns added to the table.
	 * @var array
	 */
	protected $columns = array();

	public function __construct($table_name)
	{
		$this->name = $table_name;
	}

	/**
	 * Sets the description of the table.
	 *
	 * @param string $description The new description of the table.
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->definition['description'] = $description;
		return $this;
	}

	/**
	 * Does some final processing and then returns the definition of the table
	 * schema in the form that Drupal is expecting.
	 *
	 * @return array The processed table definition.
	 */
	public function getDefinition()
	{
		// Make sure there's a fields array on the schema.
		if (!array_key_exists('fields', $this->definition) || !is_array($this->definition['fields'])) {
			$this->definition['fields'] = array();
		}

		// Add any existing columns to the table schema.
		foreach ($this->columns as $column) {
			/** @var TableColumn $column */
			$this->definition['fields'][$column->getName()] = $column->getDefinition();
		}

		return $this->definition;
	}

	/**
	 * Adds a column to the current table.
	 *
	 * @param string $name     The name of the column.
	 * @param string $type     The type of column.
	 * @param string $size     The size of the column.
	 * @param bool   $unsigned Whether or not the column is unsigned.
	 * @param bool   $null     Whether or not the column is null.
	 * @param mixed  $length   The maximum length of the column. False if none is to
	 *                         be applied.
	 *
	 * @return TableColumn The new column.
	 */
	protected function addColumn($name, $type = TableColumn::TYPE_SERIAL, $size = TableColumn::SIZE_NORMAL,
		$unsigned = false, $null = false, $length = false)
	{
		$column = new TableColumn($name, $type, $size, $unsigned, $null, $length);
		$this->columns[] = $column;
		return $column;
	}

	/**
	 * Adds a serial column to the table. Serial columns are auto-incrementing
	 * by default and are marked as unsigned. This, of course, can be changed by
	 * calling the child functions.
	 *
	 * @param        $name
	 * @param string $size
	 *
	 * @return TableColumn
	 */
	public function serial($name, $size = TableColumn::SIZE_NORMAL)
	{
		return $this->addColumn($name, TableColumn::TYPE_SERIAL, $size, true);
	}

	/**
	 * Creates a tiny serial column.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function tinySerial($name)
	{
		return $this->serial($name, TableColumn::SIZE_TINY);
	}

	/**
	 * Creates a small serial column.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function smallSerial($name)
	{
		return $this->serial($name, TableColumn::SIZE_SMALL);
	}

	/**
	 * Creates a medium serial column.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function mediumSerial($name)
	{
		return $this->serial($name, TableColumn::SIZE_MEDIUM);
	}

	/**
	 * Creates a big serial column.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function bigSerial($name)
	{
		return $this->serial($name, TableColumn::SIZE_BIG);
	}

	/**
	 * Creates a normal integer.
	 *
	 * @param        $name
	 * @param string $size
	 *
	 * @return TableColumn
	 */
	public function integer($name, $size = TableColumn::SIZE_NORMAL)
	{
		return $this->addColumn($name, TableColumn::TYPE_INTEGER, $size);
	}

	/**
	 * Creates a tiny integer.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function tinyInteger($name)
	{
		return $this->integer($name, TableColumn::SIZE_TINY);
	}

	/**
	 * Creates a small integer.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function smallInteger($name)
	{
		return $this->integer($name, TableColumn::SIZE_SMALL);
	}

	/**
	 * Creates a medium integer.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function mediumInteger($name)
	{
		return $this->integer($name, TableColumn::SIZE_MEDIUM);
	}

	/**
	 * Creates a big integer.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function bigInteger($name)
	{
		return $this->integer($name, TableColumn::SIZE_BIG);
	}

	/**
	 * Creates a float.
	 *
	 * @param string $name
	 * @param string $size
	 *
	 * @return TableColumn
	 */
	public function float($name, $size = TableColumn::SIZE_NORMAL)
	{
		return $this->addColumn($name, TableColumn::TYPE_FLOAT, $size);
	}

	/**
	 * Creates a tiny float.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function tinyFloat($name)
	{
		return $this->float($name, TableColumn::SIZE_TINY);
	}

	/**
	 * Creates a small float.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function smallFloat($name)
	{
		return $this->float($name, TableColumn::SIZE_SMALL);
	}

	/**
	 * Creates a medium float.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function mediumFloat($name)
	{
		return $this->float($name, TableColumn::SIZE_MEDIUM);
	}

	/**
	 * Creates a big float.
	 *
	 * @param string $name
	 *
	 * @return TableColumn
	 */
	public function bigFloat($name)
	{
		return $this->float($name, TableColumn::SIZE_BIG);
	}

	/**
	 * Creates a numeric column.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function numeric($name)
	{
		return $this->addColumn($name, TableColumn::TYPE_NUMERIC);
	}

	/**
	 * Adds a string (varchar) column to the table.
	 *
	 * @param     $name
	 * @param int $length
	 *
	 * @return TableColumn
	 * @throws TableDefinitionException
	 */
	public function string($name, $length = 255)
	{
		return $this->addColumn($name, TableColumn::TYPE_VARCHAR, TableColumn::SIZE_NORMAL, false, false, $length);
	}

	/**
	 * Adds a char column to the table.
	 *
	 * @param     $name
	 * @param int $length
	 *
	 * @return TableColumn
	 */
	public function char($name, $length = 10)
	{
		return $this->addColumn($name, TableColumn::TYPE_CHAR, TableColumn::SIZE_NORMAL, false, false, $length);
	}

	/**
	 * Adds a text column to the table.
	 *
	 * @param        $name
	 * @param string $size
	 *
	 * @return TableColumn
	 */
	public function text($name, $size = TableColumn::SIZE_NORMAL)
	{
		return $this->addColumn($name, TableColumn::TYPE_TEXT, $size);
	}

	/**
	 * Adds a tiny text column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function tinyText($name)
	{
		return $this->text($name, TableColumn::SIZE_TINY);
	}

	/**
	 * Adds a small text column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function smallText($name)
	{
		return $this->text($name, TableColumn::SIZE_SMALL);
	}

	/**
	 * Adds a medium text column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function mediumText($name)
	{
		return $this->text($name, TableColumn::SIZE_MEDIUM);
	}

	/**
	 * Adds a big text column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function bigText($name)
	{
		return $this->text($name, TableColumn::SIZE_BIG);
	}

	/**
	 * Adds a normal binary column to the table.
	 *
	 * @param        $name
	 * @param string $size
	 *
	 * @return TableColumn
	 */
	public function binary($name, $size = TableColumn::SIZE_NORMAL)
	{
		return $this->addColumn($name, TableColumn::TYPE_BLOB, $size);
	}

	/**
	 * Adds a big binary column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function bigBinary($name)
	{
		return $this->binary($name, TableColumn::SIZE_BIG);
	}

	/**
	 * Adds a datetime column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function dateTime($name)
	{
		return $this->addColumn($name, TableColumn::TYPE_DATETIME);
	}

	/**
	 * Adds a boolean column to the table.
	 *
	 * @param $name
	 *
	 * @return TableColumn
	 */
	public function boolean($name)
	{
		return $this->tinyInteger($name)->defaultTo(0)->length(1);
	}

	/**
	 * Adds 'created' and 'changed' columns to the table.
	 */
	public function timestamps()
	{
		$this->integer('created')->length(11)->defaultTo('CURRENT_TIMESTAMP');
		$this->integer('changed')->length(11)->defaultTo('CURRENT_TIMESTAMP');
	}

}
