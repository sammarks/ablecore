<?php

use AbleCore\Mailer\MailerException;
use AbleCore\Mailer\MailerBase;

function ablecore_mail($key, &$message, $params)
{
	if ($key == 'handler') {

		// Get the handler.
		if (!array_key_exists('handler', $params)) {
			throw new MailerException('No handler was supplied to the email. Something went wrong internally.');
		}

		// Make sure it's the correct class.
		if (!($params['handler'] instanceof MailerBase)) {
			throw new MailerException('The mail handler supplied is invalid.');
		}

		// Finally, get the handler.
		$handler = $params['handler'];
		$message['subject'] = $handler->subject;

		// Get the rendered content of the message.
		$rendered = $handler->render();
		$message['body'] = explode(PHP_EOL, $rendered);

		// Get the headers.
		$message['headers']['Content-Type'] = 'text/html';
		$message['headers'] = array_replace($message['headers'], $handler->headers);

	}
}

function ablecore_mail_theme()
{
	return AbleCore\Modules\ThemeManager::init()
		->define('ablecore_key_value_email')
		->fin();
}
