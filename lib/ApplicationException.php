<?php

class ApplicationException extends Exception {

	public function __construct($message = 'Application Error') {
		$r = new Response();

		$r->setStatus('error');
		$r->setMessage($message);
		$r->dispatch();
	}

}
