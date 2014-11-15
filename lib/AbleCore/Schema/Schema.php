<?php

namespace AbleCore\Schema;

class Schema {

	/**
	 * The running schema definition.
	 * @var array
	 */
	protected $definition = array();

	/**
	 * Add a table to the current table definition.
	 *
	 * @param string   $table_name          The name of the table to create.
	 * @param callable $definition_callback A function to call. The table definition is passed
	 *                                      to this function, and this function adds columns
	 *                                      or indexes to the table.
	 *
	 * @return $this
	 */
	public function addTable($table_name, callable $definition_callback)
	{
		$definition = new TableDefinition($table_name);
		$definition_callback($definition);
		$this->definition[$table_name] = $definition->getDefinition();
		return $this;
	}

	/**
	 * Finish the generation of the schema.
	 *
	 * @return array The generated schema.
	 */
	public function fin()
	{
		return $this->definition;
	}

}
