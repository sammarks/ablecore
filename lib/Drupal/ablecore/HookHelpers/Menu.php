<?php

namespace Drupal\ablecore\HookHelpers;

use FlorianWolters\Component\Util\Singleton\SingletonTrait;

class Menu {

	use SingletonTrait;

	/**
	 * Creates a new instance of the menu hook.
	 *
	 * @param mixed $access The access arguments to apply to the entire menu instance.
	 *                      If this is false, no access arguments will be applied.
	 *
	 * @return MenuInstance The menu instance.
	 */
	public function init($access = false)
	{
		$instance = new MenuInstance();
		if ($access !== false) {
			$instance->setAccessArguments($access);
		}
		return $instance;
	}

} 
