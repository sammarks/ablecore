<?php

namespace Drupal\ablecore\HookHelpers;

interface CrudInterface {

	public static function load($identifier);
	public static function import(array $values);
	public static function all();

	public static function getTableName();

}
