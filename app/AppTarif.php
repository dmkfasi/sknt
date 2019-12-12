<?php

class AppTarif {
	private $db = null;

	// Application Context injected here
	private $context = null;

	// TODO refactor this to config/db/whatever
	private $timezone = 'Europe/Moscow';

	public function __construct(Context $ctx) {
		$this->db = DB::getInstance();

		$this->context = $ctx;
	}

	public function getContent() {
		// Pull down args from URI
		$user_id = $this->context->getArg(1);
		$service_id = $this->context->getArg(3);

		$result = 'ok';

		// Get JSON payload as an array
		$json = json_decode($this->context->getPayload(), true);
		if (JSON_ERROR_NONE === json_last_error()) {
			$insert_id = $this->setTarifForUser((int)$user_id,
				(int)$json['tarif_id']);

			// Set JSON result code for a failed transaction
			if ($insert_id === 0) {
				$result = 'error';
			}
		} else {
			$result = 'error';
		}

		return [ 'result' => $result ];
	}

	public function setTarifForUser(int $user_id, int $tarif_id) {
		// TODO Wrap this up in a transaction
		//$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$this->db->runQuery('INSERT INTO services
		(`user_id`, `tarif_id`, `payday`)
		VALUES(:user_id, :tarif_id, CURDATE())', [
			':user_id'			=> $user_id,
			':tarif_id'		=> $tarif_id,
		]);

		return (int)$this->db->lastInsertId();
	}
}
