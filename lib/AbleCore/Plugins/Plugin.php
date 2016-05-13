<?php

namespace AbleCore\Plugins;

use AbleCore\Forms\ICompleteForm;
use AbleCore\Internal\SingletonTrait;

abstract class Plugin {

	use SingletonTrait;

	protected function __construct()
	{
		// ... Set up the plugin configuration ...

		// Create the plugin array.
		global $plugin;
		$plugin = $this->getPlugin();
	}

	/**
	 * Given a plugin's configuration array, the key
	 * of the form property and the form value, this function
	 * verifies that the form is a valid form and
	 * sets its configuration.
	 *
	 * @param                               $plugin
	 * @param                               $key
	 * @param \AbleCore\Forms\ICompleteForm $form
	 *
	 * @return bool Whether or not the form is valid.
	 */
	protected function processFormProperty(&$plugin, $key, ICompleteForm $form)
	{
		unset($plugin[$key]);
		if (!$this->isFormValid($form)) return false;

		$plugin[$key] = $this->getFormName($key, $form);
		return true;
	}

	/**
	 * Determines if the provided form is a valid form.
	 *
	 * @param ICompleteForm $form
	 *
	 * @return bool
	 */
	protected function isFormValid(ICompleteForm $form)
	{
		$empty_array = array();
		return ($form->build($empty_array, $empty_array) !== null);
	}

	/**
	 * Given a property and a loaded form, gets the complete
	 * form name based on the class of the form.
	 *
	 * @param string        $property The name of the property
	 *                                the form corresponds to.
	 * @param ICompleteForm $form     The form.
	 *
	 * @return string
	 */
	protected function getFormName($property, ICompleteForm $form)
	{
		return implode('_', array(
			strtolower(str_replace(' ', '_', get_class($form))),
			strtolower($property),
			'form',
		));
	}

	/**
	 * Converts the configuration defined in the class to a plugin array
	 * ready for exporting into the global namespace.
	 *
	 * @return mixed
	 */
	protected abstract function getPlugin();

}
