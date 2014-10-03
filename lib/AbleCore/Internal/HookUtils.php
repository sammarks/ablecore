<?php

namespace AbleCore\Internal;

class HookUtils {

	public static function getAbleCoreModules()
	{
		return module_implements('ablecore');
	}

	public static function isAbleCoreModule($module)
	{
		return function_exists($module . '_ablecore');
	}

	public static function includeDirectory($module, $directory)
	{
		$module_path = drupal_get_path('module', $module);
		$directory_path = DRUPAL_ROOT . '/' . $module_path . '/' . $directory;
		if (is_dir($directory_path)) {
			foreach (glob($directory_path . '/*.inc') as $file) {
				require_once $file;
			}
		}
	}

} 
