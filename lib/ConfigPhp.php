<?php

class ConfigPhp extends Config {

	private $filename = 'db_cfg.php';

	public function load() {
		$this->settings = [
			'driver'		=> 'mysql',
			'username'	=> DB_USER,
			'password'	=> DB_PASSWORD,
			'dbname'		=> DB_NAME,
			'host'			=> DB_HOST,
		];
	}

}
