<?php

namespace AbleCore\Mailer;

class ThemeMailer extends MailerBase {

	protected $variables = array();
	private $theme = '';

	public function __construct($theme, $subject = '', $from = null, $variables = array())
	{
		parent::__construct($subject, $from);
		$this->variables = $variables;
		$this->theme = $theme;
	}

	public static function init($theme, $subject = '', $from = null, $variables = array())
	{
		return new self($theme, $subject, $from, $variables);
	}

	public function variable($key, $value)
	{
		$this->variables[$key] = $value;
		return $this;
	}

	public function render()
	{
		return theme($this->theme, $this->variables);
	}

}
