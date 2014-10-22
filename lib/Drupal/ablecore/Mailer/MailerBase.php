<?php

namespace Drupal\ablecore\Mailer;

abstract class MailerBase {

	public $subject = '';
	public $to = array();
	public $from = null;
	public $headers = array();

	public function __construct($subject = '', $from = null)
	{
		$this->subject = $subject;
		$this->from = $from;
	}

	public function addRecipient($email, $name)
	{
		$this->to[] = "$name <$email>";
		return $this;
	}

	public function header($key, $value)
	{
		$this->headers[$key] = $value;
		return $this;
	}

	public function send()
	{
		$to = implode(', ', $this->to);
		$message = drupal_mail('ablecore', 'handler', $to, language_default(), array(
			'handler' => $this,
		), $this->from);
		if (isset($message['result']) && $message['result']) {
			return true;
		} else return false;
	}

	public abstract function render();

} 

class MailerException extends \Exception {}
