<?php

namespace AbleCore\Fields;

class FieldValueCollection extends \ArrayObject
{
	public function __get($item)
	{
		// If there is one item in the array, pass this
		// information along.
		if (count($this) > 0 && !property_exists($item, $this)) {
			// We don't do a check for the property existing here because __call might handle it.
			$first_result = reset($this);
			if (!is_object($first_result)) {
				throw new \Exception('The property ' . $item . ' could not be found.');
			}
			return $first_result->$item;
		} elseif (property_exists($item, $this)) {
			return $this->$item;
		} else {
			throw new \Exception('The property ' . $item . ' could not be found.');
		}
	}

	public function __call($item, $arguments)
	{
		// If there is one item in the array, pass this
		// information along.
		if (count($this) > 0 && !method_exists($item, $this)) {
			if (is_callable(array(reset($this), $item))) {
				return call_user_func_array(array(reset($this), $item), $arguments);
			} else {
				throw new \Exception('The method ' . $item . ' could not be found.');
			}
		} elseif (method_exists($item, $this)) {
			return call_user_func_array(array($this, $item), $arguments);
		} else {
			throw new \Exception('The method ' . $item . ' could not be found.');
		}
	}

	public function __toString()
	{
		if (count($this) > 0) {
			return (string)$this[0];
		} else {
			return '';
		}
	}

	public function first($count = 1)
	{
		$result = $this->range(0, $count);
		if (is_array($result) && count($result) === 1) {
			return $result[0];
		} else {
			return $result;
		}
	}

	public function last($count = 1)
	{
		$result = $this->range(count($this) - $count, $count);
		if (is_array($result) && count($result) === 1) {
			return $result[0];
		} else {
			return $result;
		}
	}

	public function range($offset, $length)
	{
		return array_slice($this->getArrayCopy(), $offset, $length);
	}

	public function take($length)
	{
		return $this->first($length);
	}

	public function unique()
	{
		$unique_items = array();
		foreach ($this as $item) {
			if (array_search($item, $unique_items) == false) {
				$unique_items[] = $item;
			}
		}
		return $unique_items;
	}

	/**
	 * Runs each of the items in the collection through a callback function,
	 * returning the values that each of the callback function invocations
	 * return.
	 *
	 * @param callable $callback The callback to call ($item) is passed as an
	 *                           argument to the function.
	 *
	 * @return array The results of each of the callbacks.
	 */
	public function map(callable $callback)
	{
		$result = array();
		foreach ($this as $item) {
			$result[] = $callback($item);
		}

		return $result;
	}

	/**
	 * Joins the string-equivalent of each of the items in this
	 * collection with the specified separator.
	 *
	 * If a callback is passed, it will use that callback to get the
	 * value of each of the fields instead of just taking the string
	 * representation. @see map() for more information on how to use
	 * the callback.
	 *
	 * @param string   $separator The string to separate the items with.
	 * @param callable $callback  The callback to use when getting the
	 *                            values of each of the fields.
	 *
	 * @return string The resulting string.
	 */
	public function join($separator = ', ', callable $callback = null)
	{
		if ($callback === null) {
			$callback = function ($item) {
				return (string)$item;
			};
		}
		$items = $this->map($callback);

		return implode($separator, $items);
	}
}
