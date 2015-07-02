<?php

namespace AbleCore\Forms;

interface IFormValidateable {

	/**
	 * Performs validation on the submitted form state.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 */
	public function validate($form, &$form_state);

}
