<?php

use FlorianWolters\Component\Util\Singleton\SingletonTrait;

class AbleCore {

	/**
	 * Gets a helper from the Able Core class structure.
	 *
	 * @param string $helper The name of the helper to retrieve.
	 *
	 * @return Drupal\ablecore\Helpers\Helper The requested helper if one exists.
	 */
	public static function helper($helper)
	{
		return static::getClass('helper', 'Drupal\\ablecore\\Helpers', $helper, 'Drupal\\ablecore\\Helpers\\Helper');
	}

	/**
	 * Gets a hook helper from the Able Core class structure.
	 *
	 * @param string $hook The name of the helper to retrieve (typically the name
	 *                     of the hook. Can be in CamelCase or in its original form
	 *                     with underscores).
	 *
	 * @return SingletonTrait The hook helper.
	 * @throws AbleCoreLoaderException
	 */
	public static function hook($hook)
	{
		try {
			return static::getClass('helper', 'Drupal\\ablecore\\HookHelpers', $hook);
		} catch (AbleCoreLoaderException $ex) {
			$hook = Drupal\ablecore\Helpers\Inflector::getInstance()->camelize($hook);
			return static::getClass('helper', 'Drupal\\ablecore\\HookHelpers', $hook);
		}
	}

	/**
	 * Gets a class from the Able Core namespace structure.
	 *
	 * @param string $type The type of class we're loading (helper, module, hook, etc.)
	 * @param string $prefix The namespace prefix for the classes.
	 * @param string $class The name of the class to load.
	 * @param mixed $parent_class The name of the parent class to check against, or false
	 *                            to not check.
	 *
	 * @return SingletonTrait A helper class with the singleton trait.
	 * @throws AbleCoreLoaderException
	 */
	protected static function getClass($type, $prefix, $class, $parent_class = false)
	{
		$class_name = $prefix . '\\' . $class;
		if (class_exists($class_name)) {
			if ($parent_class !== false && is_subclass_of($class_name, $parent_class)) {
				return forward_static_call(array($class_name, 'getInstance'));
			}
		}

		throw new AbleCoreLoaderException('The ' . $type . ' ' . $class . ' does not exist.');
	}

}

class AbleCoreLoaderException extends \Exception {}
