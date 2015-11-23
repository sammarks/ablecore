<?php

namespace AbleCore\Modules;

abstract class Crud implements CrudInterface {

	/**
	 * The keys to serialize/unserialize automatically when saving/loading to/from
	 * the database.
	 * @var array
	 */
	protected $serialized = array();

	protected static function getPrimaryKey()
	{
		$schema = drupal_get_schema(self::getTableNameInternal());
		if (!empty($schema['primary key'][0]))
			return $schema['primary key'][0];

		return false;
	}

	protected static function getPrimaryKeyInternal()
	{
		$class = get_called_class();
		return $class::getPrimaryKey();
	}

	protected static function getTableNameInternal()
	{
		$class = get_called_class();
		return $class::getTableName();
	}

	/**
	 * All
	 *
	 * Returns a list of all items, sorted by weight if available.
	 *
	 * @param bool $reset Whether or not to reset the static cache.
	 *
	 * @return array An array of the results. Empty array if none available.
	 */
	public static function all($reset = false)
	{
		$cache = &drupal_static(__FUNCTION__, array(), $reset);
		if (!isset($cache[self::getTableNameInternal()])) {
			$table = self::getTableNameInternal();
			$query = db_select($table, 't')
				->fields('t');
			if (property_exists(get_called_class(), 'weight')) {
				$query->orderBy('weight');
			}
			$result = $query->execute();
			$results = array();
			while ($row = $result->fetchAssoc()) {
				$results[] = self::import($row);
			}
			$cache[self::getTableNameInternal()] = $results;
		}
		return $cache[self::getTableNameInternal()];
	}

	/**
	 * Load
	 *
	 * Loads an existing instance of the current class from the database.
	 *
	 * @param int  $identifier The ID of the instance to load.
	 * @param bool $reset      Whether or not to reset the static cache.
	 *
	 * @return bool|Crud false if the instance wasn't found. Else, the object.
	 * @throws \Exception
	 */
	public static function load($identifier, $reset = false)
	{
		$cache = &drupal_static(__FUNCTION__, array(), $reset);
		if (!isset($cache[self::getTableNameInternal()][$identifier])) {
			$table = self::getTableNameInternal();
			$primary_key = self::getPrimaryKeyInternal();
			if (!$primary_key) {
				throw new \Exception('A primary key could not be found for ' . $table);
			}
			$result = db_select($table, 't')
				->fields('t')
				->condition($primary_key, $identifier)
				->range(0, 1)
				->execute();
			$row = $result->fetchAssoc();
			if ($row) {
				$class = get_called_class();
				$cache[self::getTableNameInternal()][$identifier] = $class::import($row);
			} else {
				$cache[self::getTableNameInternal()][$identifier] = false;
			}
		}
		return $cache[self::getTableNameInternal()][$identifier];
	}

	/**
	 * Imports
	 *
	 * Takes an array and creates an instance of this class from it.
	 *
	 * @param array $values The values to use for the import.
	 *
	 * @return Crud
	 */
	public static function import(array $values)
	{
		$class = get_called_class();
		$instance = new $class();
		$serialized = $instance->serialized;
		foreach ($values as $key => $value) {
			if (in_array($key, $serialized)) {
				$value = unserialize($value);
			}
			$instance->$key = $value;
		}

		return $instance;
	}

	/**
	 * Saves the current instance to the database.
	 *
	 * @param bool $force_new When this is true, the fact that the identifier for this object is set will be ignored,
	 *                        and a new record will be written to the database regardless.
	 *
	 * @return bool|int The results of drupal_write_record
	 * @see drupal_write_record
	 */
	public function save($force_new = false)
	{
		$primary_key = self::getPrimaryKey();
		$primary_keys = array($primary_key);
		if (!$this->$primary_key) {
			$force_new = true;
		}
		if ($force_new === true) {
			$primary_keys = array();
		}

		// Create a clone.
		$save_clone = clone $this;
		foreach (get_object_vars($save_clone) as $key => $value) {
			if (in_array($key, $this->serialized)) {
				$save_clone->$key = serialize($value);
			}
		}

		$result = drupal_write_record(self::getTableNameInternal(), $save_clone, $primary_keys);

		$this->$primary_key = $save_clone->$primary_key;
	}

	/**
	 * Delete
	 *
	 * Deletes the current object and returns the result.
	 *
	 * @return \DatabaseStatementInterface
	 */
	public function delete()
	{
		$primary_key = self::getPrimaryKey();
		$table = self::getTableNameInternal();
		$id = $this->$primary_key;
		return db_delete($table)
			->condition($primary_key, $id)
			->execute();
	}

}
