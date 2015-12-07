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

	public static function includeDirectory($module, $directory, $recursive = true)
	{
		$module_path = drupal_get_path('module', $module);
		$directory_path = DRUPAL_ROOT . '/' . $module_path . '/' . $directory;
		if (is_dir($directory_path)) {
			if ($recursive) {
				$dir = new \RecursiveDirectoryIterator($directory_path);
				$ite = new \RecursiveIteratorIterator($dir);
				$files = new \RegexIterator($ite, '%^.*\.inc$%', \RegexIterator::GET_MATCH);
				foreach ($files as $sub_files) {
					foreach ($sub_files as $file) {
						if (file_exists($file)) {
							require_once $file;
						}
					}
				}
			} else {
				foreach (glob($directory_path . '/**.inc') as $file) {
					require_once $file;
				}
			}
		}
	}

} 
