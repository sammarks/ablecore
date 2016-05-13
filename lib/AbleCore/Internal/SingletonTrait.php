<?php

/**
 * Majority of the logic from this file was shamelessly stolen from Florian Wolter's Singleton Trait
 * component (as well as the dependencies of it). The original code can be found at:
 *
 * http://github.com/FlorianWolters/PHP-Component-Util-Singleton
 *
 * (Note: The code is not included via Composer because Drupal's does not support modules using composer)
 *
 * @author Florian Wolters <wolters.fl@gmail.com>
 */

namespace AbleCore\Internal;

trait SingletonTrait {

	/**
	 * Returns the *Singleton* instance of the class using this trait.
	 *
	 * @staticvar static[] $instances The *Singleton* instances of the classes
	 *                                using this trait.
	 *
	 * @return static The *Singleton* instance.
	 */
	final public static function getInstance()
	{
		static $instances = [];
		$className = get_called_class();

		if (false === isset($instances[$className])) {

			$arguments = \func_get_args();
			$reflectedClass = new \ReflectionClass($className);
			$newInstance = $reflectedClass->newInstanceWithoutConstructor();
			$reflectedConstructor = $reflectedClass->getConstructor();

			if (null !== $reflectedConstructor) {
				$reflectedConstructor->setAccessible(true);
				$reflectedConstructor->invokeArgs($newInstance, $arguments);
			}

			$instances[$className] = $newInstance;

		}

		return $instances[$className];
	}

	// @codeCoverageIgnoreStart

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator.
	 */
	protected function __construct()
	{
		// NOOP
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	final private function __clone()
	{
		// NOOP
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 *
	 * @noinspection PhpUnusedPrivateMethodInspection
	 */
	final private function __wakeup()
	{
		// NOOP
	}

	// @codeCoverageIgnoreEnd
}
