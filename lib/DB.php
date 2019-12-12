<?php

class DB extends PDO {

	private static $instance = null;

  private function __construct()
  {
		$cfg = Config::getInstance('php');

		// Construct DSN since ini file can't contain equal sign in a value
		$dsn = "{$cfg->driver}:dbname={$cfg->dbname};host={$cfg->host}";

		try {
	    parent::__construct($dsn, $cfg->username, $cfg->password);
		} catch (PDOException $e) {
			throw new ApplicationException('Unable to instantiate PDO driver: ' . $e->getMessage());
		}
  }

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new DB();
		}

		return self::$instance;
	}

	public function runQuery(string $sql, array $params = []) {
    try {
      $sth = $this->prepare($sql);
      $sth->execute($params);
    } catch (PDOException $e) {
      throw new ApplicationException('Unable to prepare SQL: ' . $e->getMessage());
    }

    return $sth;
	}

  public function getResults(string $sql, array $params = []) {
    $sth = $this->runQuery($sql, $params);

		return $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getResult(string $sql, array $params = []) {
    $sth = $this->runQuery($sql, $params);

		return $sth->fetch(PDO::FETCH_ASSOC);
  }
}
