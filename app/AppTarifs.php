<?php

// TODO introduce an Interface to all App objects
class AppTarifs {

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

		$tarif	= $this->getTarif($user_id, $service_id);
		$tarifs = $this->getTarifs($tarif['tarif_id']);

		foreach ($tarifs as $k => $v) {
			try {
				// Get new DateTime object and add billing period to it
				$payday = new DateTime('today midnight', $this->getTimeZone());

				// Add period of months specified in the dataset
				$added_period = sprintf('P%dM', (int)$v['pay_period']);
				$payday->add(new DateInterval($added_period));

				// Store new billing unixtime
				$tarifs[$k]['new_payday'] = $payday->format('UO');
			} catch (Exception $e) {
				// TODO exception handling
			}
		}

		// Plunge in payment plans
		$tarif['tarifs'] = $tarifs;

		return [
			'tarifs' => $tarif,
		];
	}

	public function getTarif(int $user_id, int $service_id) {
		$query = "SELECT * FROM services s INNER JOIN tarifs t ON s.tarif_id = t.ID";

		// TODO empty set handling
		return $this->db->getResult(
			"{$query} WHERE s.user_id = :user_id AND s.ID = :service_id", [
				'user_id' => $user_id,
				'service_id' => $service_id,
			]);
	}

	public function getTarifs(int $tarif_id) {
		$query = "SELECT * FROM tarifs t INNER JOIN services s ON s.tarif_id = t.ID";

		// TODO empty set handling
		return $this->db->getResults("{$query} WHERE t.tarif_group_id = :tarif_id", [
			'tarif_id' => $tarif_id,
		]);
	}

	public function getTimeZone() {
		// TODO Resolve this with DI and better exception handling
		try {
			return new DateTimeZone($this->timezone);
		} catch (Exception $e) {
			throw new ApplicationException("Invalid TimeZone: {$e->getMessage()}");
		}
	}
}
