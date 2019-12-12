<?php

// TODO Refactor this to work with a URL class
class Context {

	private $uri = '';
	private $payload = '';

	private $action = null;
	private $subject = null;
	private $argv = null;

	private $content = null;

	public function __construct() {
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri($uri) {
		$this->uri = $uri;
	}

	public function getPayload() {
		return $this->payload;
	}

	public function setPayload($payload) {
		$this->payload = $payload;
	}

	public function getAction() {
		return $this->action;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function getArgs() {
		return $this->argv;
	}

	public function setArgs(array $argv) {
		$this->argv = $argv;
	}

	// Returns individual argument
	public function getArg(int $arg) {
		if (isset($this->argv[$arg])) {
			return $this->argv[$arg];
		} else {
			return false;
		}
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}
}
