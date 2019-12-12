<?php

// Refactor this to work with a URL class
class Context {

	private $uri = '';
	private $path = '';
	private $method = 'UNKNOWN';

	private $action = null;
	private $subject = null;
	private $argv = null;

	private $content = null;

	// TODO refactor to Config or DB or whatever
	private $action_list = [
		'User', 'Tarif',
		];

	public function __construct() {
		$this->setRequestMethod();
		$this->setUri();
		$this->setPath();
		$this->setRoute();
	}

	public function getRequestMethod() {
		return $this->method;
	}

	public function setRequestMethod() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$this->method = $_SERVER['REQUEST_METHOD'];
		}
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->uri = $_SERVER['REQUEST_URI'];
		}
	}

	public function getPath() {
		return $this->path;
	}

	public function setPath() {
		// Strip out 'api' part
		$uri = str_replace('api', '', $this->uri);
		$this->path = trim($uri, '/');

		// Get action that is coming from Query String
		$this->argv = explode('/', $this->path);
		$this->action = array_pop($this->argv);
	}

	public function getAction() {
		return $this->action;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getArgs() {
		return $this->argv;
	}

	public function getArg(int $arg) {
		if (isset($this->argv[$arg])) {
			return $this->argv[$arg];
		} else {
			return false;
		}
	}

	// Sets argument vector to run an application
	// TODO refactor
	public function setRoute() {
		// Collect all the arguments and lightly sanitize them
		foreach ($_POST as $k => $v) {
			if (!empty($v)) {
				$this->argv[$k] = htmlspecialchars($v);
			}
		}
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}
}
