<?php

namespace Drupal\ablecore_menu;

class MenuPathRelationship {

	protected $record = array();

	public function __construct()
	{
		$this->record = array(
			'pid' => null,
			'mlid' => 0,
			'path' => '',
			'type' => 'wildcard',
			'weight' => 0,
		);
	}

	public static function load($identifier)
	{
		$result = db_select('ablecore_menu_item_path', 't')
			->fields('t')
			->condition('pid', $identifier)
			->range(0, 1)
			->execute()
			->fetchAll();
		if (count($result) > 0) {
			$item = new self();
			$item->setDefinition($result[0]);
			return $item;
		} else {
			return false;
		}
	}

	public function setDefinition($query_object)
	{
		$this->record = (array)$query_object;
	}

	public function set($key, $value)
	{
		if (!array_key_exists($key, $this->record)) {
			throw new \Exception('The key ' . $key . ' does not exist.');
		}
		$this->record[$key] = $value;
	}

	public function get($key)
	{
		if (!array_key_exists($key, $this->record)) {
			throw new \Exception('The key ' . $key . ' does not exist.');
		}
		return $this->record[$key];
	}

	public function menuLink()
	{
		$mlid = $this->get('mlid');
		if (!$mlid) return false;
		return menu_link_load($mlid);
	}

	public static function create()
	{
		return new self();
	}

	public function save()
	{
		// Check to see if the ID is numeric and exists.
		$does_exist = (is_numeric($this->record['pid']) && self::load($this->record['pid']) !== false);
		$primary_keys = array();
		if ($does_exist) {
			$primary_keys = 'pid';
		}
		return drupal_write_record('ablecore_menu_item_path', $this->record, $primary_keys);
	}

	public function delete()
	{
		if (!is_numeric($this->record['pid']) || self::load($this->record['pid']) === false) {
			throw new \Exception('The record being deleted does not exist.');
		}
		db_delete('ablecore_menu_item_path')
			->condition('pid', $this->record['pid'])
			->execute();
	}

	public function match($path)
	{
		$record_path = $this->record['path'];
		$record_type = $this->record['type'];

		switch ($record_type) {
			case 'wildcard':
				return drupal_match_path($path, $record_path);
			case 'regex':
				$regex_result = preg_match_all('^' . $record_path . '^', $path);
				return !($regex_result === 0 || $regex_result === false);
		}

		return false;
	}

} 
