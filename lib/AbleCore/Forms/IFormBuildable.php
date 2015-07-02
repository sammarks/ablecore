<?php

namespace AbleCore\Forms;

interface IFormBuildable {

	/**
	 * Builds the contents of the form.
	 *
	 * @param array $form       The existing form array.
	 * @param array $form_state A reference to the existing form state.
	 *
	 * @return array A render array representing the form.
	 */
	public function build($form, &$form_state);

}
