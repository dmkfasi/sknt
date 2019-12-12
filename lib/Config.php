<?php

abstract class Config {

	private static $instance = null;

	protected $settings = null;

	private function __construct() {
	}

	public function setFilename(string $filename) {
		$this->filename = $filename;
	}

	public static function getInstance(string $type) {
		if (self::$instance === null) {
			switch ($type) {
				case 'php':
					self::$instance = new ConfigPhp();
					break;
				case 'ini':
				default:
					self::$instance = new ConfigIni();
					break;
			}
		}

		return self::$instance;
	}

	public function __get(string $var) {
		if ($this->settings === null) {
			$this->load();
		}

		return $this->settings[$var];
	}
}
