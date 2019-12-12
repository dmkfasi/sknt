<?php

// TODO introduce an Interface to all App objects
class AppTarifs {

	// Application Context injected here
	private $context = null;

	// Tarifs type Object specific properties
	private $table = null;
	private $id = null;

	public function __construct(Context $ctx) {
		$this->context = $ctx;

		// Extract table name and id
		$args = $ctx->getArgs();

		// Setup args from URI
		$this->table = $ctx->getAction();
		$this->user_id = $ctx->getArg(1);
		$this->service_id = $ctx->getArg(3);
	}

	public function getContent() {
		$db = DB::getInstance();

var_dump($this->table);
		if ($this->isAllowed()) {
			$base_sql = "SELECT * FROM {$this->table}";
		}

		// TODO empty set handling
		if (!empty($this->id)) {
			return $db->getResults("{$base_sql} WHERE user_id = :user_id", [ 'user_id' => $this->user_id ]);
		} else {
			return $db->getResults($base_sql);
		}
	}

	public function isAllowed() {
		return !(in_array($this->table, $this->blacklist));
	}
}
