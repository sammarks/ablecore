<?php

namespace AbleCore\Modules;

interface CrudInterface {

	public static function load($identifier);
	public static function import(array $values);
	public static function all();

	public static function getTableName();

}
