<?php

namespace Drupal\ablecore\Mailer;

class KeyValueMailer extends ThemeMailer {

	public function __construct($subject = '', $from = null)
	{
		$variables = array(
			'pairs' => array(),
			'header' => '',
			'footer' => '',
		);
		parent::__construct('ablecore_key_value_email', $subject, $from, $variables);
	}

	public function headerMessage($message)
	{
		return $this->variable('header', $message);
	}

	public function footerMessage($message)
	{
		return $this->variable('footer', $message);
	}

	public function add($key, $value)
	{
		if ($value === '' || $value === null) {
			$value = '(empty)';
		}
		if ($value === false) {
			$value = 'No';
		}
		if ($value === true) {
			$value = 'Yes';
		}
		$this->variables['pairs'][$key] = $value;
		return $this;
	}

} 
