<?php

/* 
 * Implements basic HTTP response object
 */

class Response {

  public $result = 'ok';
  private $content_type = 'application/json';
	private $content = '';

  public function setStatus(string $result) {
    $this->result = $result;
  }

  public function setPayload($payload) {
		// Convert any output to an array
		if (!is_array($payload)) {
			$payload = [ $payload ];
		}

		$this->payload = $payload;
	}

  public function setMessage(string $message) {
    $this->message = $message;
  }

  public function setContentType(string $mime) {
    $this->content_type = $mime;
  }

  public function toJson() {
    return $this->toJson();
  }

  public function dispatch() {
    $content = json_encode($this->content, JSON_PRETTY_PRINT);

    header('Content-type: ' . $this->content_type);
    header('Content-Length: ' . strlen($content));
    die($content);
  }

	public function setup(Context $ctx) {
		// Collect output buffer from Context to
		// convert it to an actual HTTP response
		$content = $ctx->getContent();

		// Override response type and data if set with Context
		if (isset($content['content_type'])) {
			$this->setContentType($output['content_type']);
		}

		// Override result code from the component called
		if (isset($content['result'])) {
			$this->result = $content['result'];
		}

		// Append result code to the component's output
		$content['result'] = $this->result;

		$this->content = $content;
	}
  
}
