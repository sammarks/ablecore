<?php

namespace AbleCore\Fields;

class FieldValueCollection extends \ArrayObject
{
	public function __get($item)
	{
		// If there is one item in the array, pass this
		// information along.
		if (count($this) == 1 && !property_exists($item, $this)) {
			return $this[0]->$item;
		} else {
			return $this->$item;
		}
	}

	public function __call($item, $arguments)
	{
		// If there is one item in the array, pass this
		// information along.
		if (count($this) == 1 && !method_exists($item, $this)) {
			return call_user_func_array(array($this[0], $item), $arguments);
		} else {
			return call_user_func_array(array($this, $item), $arguments);
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

	public function first()
	{
		if (count($this) > 0) {
			return $this[0];
		} else {
			return null;
		}
	}

	public function last()
	{
		if (count($this) > 0) {
			return $this[count($this) - 1];
		} else {
			return null;
		}
	}

	public function range($offset, $length)
	{
		return array_slice($this->getArrayCopy(), $offset, $length);
	}

	public function take($length)
	{
		return array_slice($this->getArrayCopy(), 0, $length);
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
}
