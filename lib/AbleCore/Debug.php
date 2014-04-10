<?php

/**
 * Debug Helper Class
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace AbleCore;

/**
 * Debug Helper Class
 *
 * This class contains a helper debug function for Drupal
 * applications.
 *
 * Use this class in your Drupal applications in place of writing
 * complicated `die` statements. It's also advisable to just debug
 * to watchdog through this function so that you can go back and
 * look at what you output in the past.
 *
 * This class is better than what core has to offer for the following
 * reasons:
 *
 * - `watchdog()` is the only core function for debug.
 * - Automatically adds `<pre>` tags around data.
 * - "Smart" detection - determines if the data is a string, array, or object.
 * - Four different modes: die, print, return and watchdog.
 * - Tries to detect the name of the variable, outputting it if
 * it found a matching value.
 *
 * #### Usage Examples
 *
 *     // Die the variable.
 *     AbleCore\Debug::di($variable);
 *
 *     // Print the debug information.
 *     AbleCore\Debug::pr($variable);
 *
 *     // Return the debug information.
 *     $debug = AbleCore\Debug::re($variable);
 *
 *     // Send the debug information to watchdog.
 *     AbleCore\Debug::wd($variable);
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 *
 */
class Debug
{

	/**
	 * Debug
	 *
	 * Displays debug information with the specified configuration.
	 *
	 * Accepted Methods:
	 *
	 * - die - Stops all PHP and displays the message.
	 * - print - Prints the debug information to the page.
	 * - return - Returns the debug information as a string.
	 * - watchdog - Submits the debug information to Drupal's watchdog.
	 *
	 * > Refer to [the Drupal API](http://api.drupal.org/api/drupal/includes!bootstrap.inc/function/watchdog/7)
	 * > for more information about the `$severity` argument.
	 *
	 * @param  mixed   $data           The data to be displayed.
	 * @param  string  $method         The method to use (defaults to watchdog).
	 * @param  integer $severity       When using watchdog, the severity of the message.
	 *
	 * @return mixed                   Depends on `$method`. Returns nothing, unless `$method` is 'return'.
	 * @deprecated
	 */
	public static function debug($data, $method = 'watchdog', $severity = WATCHDOG_NOTICE)
	{
		trigger_error('The AbleCore\Debug::debug function is deprecated. Please refer to http://ablecoredocs.sammarks.me' .
		' for updated documentation on how to use this class.');
		switch ($method) {
			case 'die':
				die(self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>');
				break;
			case 'print':
				print(self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>');
				break;
			case 'return':
				return self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>';
				break;
			case 'watchdog':
				watchdog('ae_debug',
					self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>',
					array(),
					$severity);
				break;
		}
	}

	/**
	 * Die (di)
	 *
	 * Displays debug information for the specified variable.
	 *
	 * @param  mixed $data The data to debug.
	 *
	 * @return void
	 */
	public static function di($data)
	{
		die(self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>');
	}

	/**
	 * Print (pr)
	 *
	 * Prints debug information for the specified variable.
	 *
	 * @param  mixed $data The data to debug.
	 *
	 * @return void
	 */
	public static function pr($data)
	{
		print(self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>');
	}

	/**
	 * Return (re)
	 *
	 * Returns debug information for the specified variable.
	 *
	 * @param  mixed $data The data to debug.
	 *
	 * @return string       The output content.
	 */
	public static function re($data)
	{
		return self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>';
	}

	/**
	 * Watchdog (wd)
	 *
	 * Submits debug information to watchdog for the specified variable.
	 *
	 * @param  mixed $data     The data to debug.
	 * @param  int   $severity When using watchdog, the severity of the message.
	 *
	 * @return void
	 */
	public static function wd($data, $severity = WATCHDOG_NOTICE)
	{
		watchdog('ac_debug',
			self::_getVarName($data) . '<pre>' . self::_prepareData($data) . '</pre>',
			array(),
			$severity);
	}

	/**
	 * Prepare Data
	 *
	 * Internal function to prepare data for debug.
	 *
	 * @param  mixed $data The data to prepare.
	 *
	 * @return mixed        The prepared data.
	 */
	private static function _prepareData($data)
	{
		if (is_array($data) || is_object($data))
			return print_r($data, 1);
		else {
			return $data;
		}
	}

	/**
	 * Get Variable Name
	 *
	 * Internal function to grab the variable name.
	 *
	 * @param  mixed $v   The variable to grab the name from.
	 *
	 * @return string      The variable name. FALSE if it couldn't
	 *                         be found.
	 */
	private static function _getVarName($v)
	{
		$trace = debug_backtrace();
		$vLine = file($trace[1]['file']);
		$fLine = $vLine[$trace[1]['line'] - 1];
		preg_match("#\\$(\w+)#", $fLine, $match);
		if (isset($match[0])) {
			return $match[0] . ': ';
		}
	}

}
