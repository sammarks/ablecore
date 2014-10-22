<?php

namespace Drupal\ablecore\Forms;

abstract class FormBase {

	public abstract function build($form, &$form_state);
	public function submit($form, &$form_state) { return; }
	public function validate($form, &$form_state) { return; }

} 
