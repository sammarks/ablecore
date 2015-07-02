<?php

namespace AbleCore\Forms;

abstract class FormBase implements IFormBuildable, IFormSubmittable, IFormValidateable {

	public abstract function build($form, &$form_state);
	public function submit($form, &$form_state) { return; }
	public function validate($form, &$form_state) { return; }

} 
