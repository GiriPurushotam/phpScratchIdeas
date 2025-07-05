<?php

class Repository {

	public function __construct(private Database $db)
	{
		
	}
	public function getAll(): array {
		
		$pdo = $this->db->getConnection();

		$stmt = $pdo->query("SELECT * FROM student");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}