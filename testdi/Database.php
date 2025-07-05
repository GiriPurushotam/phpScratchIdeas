<?php

class Database {



	public function __construct(private string $host, private string $database, private string $username, private string $password)
	{
		
	}

	public function getConnection(): PDO {
		$dsn = "mysql:host={$this->host}; dbname={$this->database}";

		return new PDO($dsn, $this->username, $this->password);
	}
}