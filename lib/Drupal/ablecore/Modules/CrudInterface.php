<?php

namespace Drupal\ablecore\Modules;

interface CrudInterface {

	public static function load($identifier);
	public static function import(array $values);
	public static function all();

	public static function getTableName();

}
