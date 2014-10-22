<?php

/**
 * The base DrupalExtension class.
 *
 * This class is inherited by any other classes that extend
 * an aspect of Drupal core. For example, the Node class inherits
 * this one because it has an underlying node object.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */

namespace Drupal\ablecore;

/**
 * Drupal Extension
 *
 * This class is inherited by any other classes that extend
 * an aspect of Drupal core. For example, the Node class inherits
 * this one because it has an underlying node object.
 *
 * It's important to note that with this class, if you ever
 * get an exception like:
 *
 * > Base hasn't been created yet!
 *
 * That means whatever object you're trying to access doesn't exist.
 * For example, if you were trying to access a node, and you
 * supplied a node ID that doesn't exist, the base wouldn't be
 * created, and you would get this exception.
 *
 * @package Able Core
 * @author  Samuel Marks <sam@sammarks.me>
 */
class DrupalExtension
{

	/**
	 * The underlying Drupal object.
	 * @var object
	 */
	protected $base;

	/**
	 * Call (Magic Method)
	 *
	 * Checks to see if a method is available on the parent class.
	 * If it is not available on the parent class, check to see
	 * if it is available on the base class. If it is not available
	 * on the child class, throw an exception.
	 *
	 * @param  string $method    The name of the method called.
	 * @param  array  $arguments Any arguments passed to that method.
	 *
	 * @return mixed             Whatever the function returns.
	 * @throws \Exception
	 */
	public function __call($method, $arguments)
	{
		if (!$this->base) {
			throw new \Exception("Base hasn't been created yet!");
		}

		// If the function exists in this class, call it.
		if (method_exists($this, $method)) {
			return call_user_func(array($this, $method), $arguments);
		} // If not, and it exists in $this->base, call it.
		elseif (method_exists($this->base, $method)) {
			$value = call_user_func(array($this->base, $method), $arguments);
			if ($value === null || !isset($value)) {
				return $this;
			}
		} // If it doesn't exist at all, throw an exception.
		else {
			throw new \Exception('The method: ' . $method . ' doesn\'t exist!');
		}

		// Return null if nothing worked.
		return null;
	}

	/**
	 * Get (Magic Method)
	 *
	 * Checks to see if the value is available on the parent class.
	 * If it is not available on the parent class, check to see
	 * if it is available on the base class. If it is not available
	 * on the child class, throw an exception.
	 *
	 * @param  string $name The name of the variable being accessed.
	 *
	 * @return mixed        Whatever the value of the variable is.
	 * @throws \Exception
	 */
	public function __get($name)
	{
		if (!$this->base) {
			throw new \Exception("Base hasn't been created yet!");
		}

		if ($name == 'base') {
			return $this->base;
		}

		if (!isset($this->base->$name)) {
			throw new \Exception('The property ' . $name . ' doesn\'t exist on the base.');
		} else {
			return $this->base->$name;
		}
	}

	/**
	 * Set (Magic Method)
	 *
	 * Checks to see if the variable is available on the parent class.
	 * If it is not available on the parent class, check to see
	 * if it is available on the base class. If it is not available
	 * on the base class, throw an exception.
	 *
	 * @param string $name  The name of the variable to set.
	 * @param mixed  $value The value to set it to.
	 *
	 * @throws \Exception
	 */
	public function __set($name, $value)
	{
		if ($name == 'base') {
			$this->base = $value;
			return;
		}
		if (!$this->base) {
			throw new \Exception("Base hasn't been created yet!");
		}
		$this->base->$name = $value;
	}

	/**
	 * Isset (Magic Method)
	 *
	 * Checks to see if the variable on the parent or child
	 * class is set. If the base doesn't exist, it throws
	 * an exception.
	 *
	 * @param  string $name The name of the variable to check for.
	 *
	 * @return boolean       Whether or not it is set.
	 * @throws \Exception
	 */
	public function __isset($name)
	{
		if ($name == 'base') {
			return isset($this->base);
		}
		if (!$this->base) {
			throw new \Exception("Base hasn't been created yet!");
		}
		return isset($this->base->$name);
	}

	/**
	 * Unset (Magic Method)
	 *
	 * Unsets a variable on the parent or child class.
	 * If the base doesn't exist, it throws an exception.
	 *
	 * @param string $name The name of the variable to unset.
	 *
	 * @throws \Exception
	 */
	public function __unset($name)
	{
		if ($name == 'base') {
			unset($this->base);
		}
		if (!$this->base) {
			throw new \Exception("Base hasn't been created yet!");
		}
		unset($this->base->$name);
	}

}
