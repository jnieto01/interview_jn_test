<?php

class DB {
	private $connection;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		$this->connection = new mysqli($hostname, $username, $password, $database, $port);

		if ($this->connection->connect_error) {
			die("No se encontró el servidor");	
		}
		$this->connection->set_charset("utf8");
		$this->connection->query("SET SQL_MODE = ''");
	}

	public function query($sql) {
		$query = $this->connection->query($sql);

		if ($this->connection->errno) {
			die("No se pudo realizar la consulta");
		}
		
		return $query; 
	}

	public function escape($value) {
		return $this->connection->real_escape_string($value);
	}
	
	public function countAffected() {
		return $this->connection->affected_rows;
	}

	public function getLastId() {
		return $this->connection->insert_id;
	}
	
	public function connected() {
		return $this->connection->connected();
	}

	public function __destruct() {
		$this->connection->close();		
	}
}

?>