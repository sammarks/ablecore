<?php

namespace AbleCore\Forms;

interface IFormSubmittable {

	/**
	 * Handles submission of the form.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 */
	public function submit($form, &$form_state);

}
