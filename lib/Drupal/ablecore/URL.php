<?php

/**
 * URL helper for Drupal applications.
 *
 * Provies some helper functions pertaining to URLs.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace Drupal\ablecore;

/**
 * URL
 *
 * This is a URL helper for Drupal applications. It provides
 * some helper functions pertaining to URLs.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */
class URL
{

	/**
	 * Clean
	 *
	 * Prepare a string to be used as a url argument
	 *
	 * @param  string $input_string The string to convert to argument syntax.
	 *
	 * @return string               The converted string.
	 */
	public static function clean($input_string)
	{
		module_load_include('inc', 'pathauto');
		if (function_exists('pathauto_cleanstring')) {
			return pathauto_cleanstring($input_string);
		} else {
			$os = strtolower($input_string);
			$os = preg_replace('/(\/| )/', "-", $os);
			$os = preg_replace('/[^-a-z0-9]/', "", $os);
			$os = preg_replace('/--/', "-", $os);
			return $os;
		}
	}

}
