<?php

class Request {

	protected $context = null;

	public function __construct(Context $ctx) {
		$this->context = $ctx;

		$method = $this->context->getRequestMethod();

		switch ($method) {
			case 'HEAD':
			default:
				throw new ApplicationException("HTTP method '{$method}' is not supported");
				break;

			case 'PUT':
			case 'GET':
			case 'POST':
				$this->runPost();
				break;
		}
	}

	private function runPost() {
		$obj_name = 'App' . ucfirst($this->context->getAction());

		if (class_exists($obj_name)) {
			// Pass by flow Context to the Object
			$app = new $obj_name($this->context);

			// Assert App content into context for output
			$this->context->setContent($app->getContent());
		} else {
			throw new ApplicationException('Requested method not found');
		}
	}

}
