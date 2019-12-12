<?php

class ConfigIni extends Config {

	private $filename = 'config.ini';

	public function load() {

		if (empty($this->filename)) {
			throw new ApplicationException('No config file name specified');
		} elseif (!is_readable($this->filename)) {
			throw new ApplicationException('Specified config file is not readable');
		}

		$this->settings = parse_ini_file($this->filename);
	}

	public function setFilename(string $filename) {
		$this->filename = $filename;
	}
}
