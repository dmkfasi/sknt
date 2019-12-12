<?php

class Request {

	private $method = 'UNKNOWN';

	private $context = null;
	private $path = null;

	public function __construct(Context $ctx) {
		$this->context = $ctx;

		$this->setupRequestMethod();
		$this->setupUri();
		$this->setupRoute();
		$this->setupContext();
		$this->setContextArgs();

		switch ($this->method) {
			case 'PUT':
			case 'GET':
			case 'POST':
				$this->runComponent();
				break;

			case 'HEAD':
			default:
				throw new ApplicationException("HTTP method '{$method}' is not supported");
				break;
		}
	}

	public function getRequestMethod() {
		return $this->method;
	}

	public function setupRequestMethod() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$this->method = $_SERVER['REQUEST_METHOD'];
		}
	}

	public function setupUri() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->uri = $_SERVER['REQUEST_URI'];
		}
	}

	// Sets argument vector to run an application
	// TODO refactor
	public function setContextArgs() {
		$argv = [];

		// Collect all the arguments based on request method and lightly sanitize them
		switch ($this->method) {
			case 'POST':
				foreach ($_POST as $k => $v) {
					if (!empty($v)) {
						$argv[$k] = htmlspecialchars($v);
					}
				}
				break;

			case 'GET':
				foreach ($_GET as $k => $v) {
					if (!empty($v)) {
						$argv[$k] = htmlspecialchars($v);
					}
				}
				break;

			case 'PUT':
				// TODO error handling
				$str = '';
		    $str = file_get_contents("php://input");
				$this->context->setPayload($str);
				break;
		}

		$this->argv = $argv;
	}

	public function setupRoute() {
		// Strip out 'api' part
		$uri = str_replace('api', '', $this->uri);
		$this->path = trim($uri, '/');
	}

	// TODO Split this apart
	public function setupContext() {
		// Get action that is coming from Query String
		$this->argv = explode('/', $this->path);
		$this->action = array_pop($this->argv);

		$this->context->setArgs($this->argv);
	}

	private function runComponent() {
		$obj_name = 'App' . ucfirst($this->action);

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
