<?php
class DatabaseManager {
	private $db;

	function __construct() {
		$DB_HOST = getenv("POSTGRES_HOST");
		$DB_NAME = getenv("POSTGRES_DB");
		$DB_USER = getenv("POSTGRES_USER");
		$DB_PASSWORD = getenv("POSTGRES_PASSWORD");
		try {
			$this->db = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME",
				$DB_USER,
				$DB_PASSWORD,
				array(
					PDO::ATTR_TIMEOUT => 5,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
		} catch (PDOexception $e) {
			echo "Connection failed: " . $e->getMessage();
			exit();
		}
		$create_db_commands = file_get_contents(__DIR__ . "/create_tables.sql");
		try {
			$this->db->exec($create_db_commands);
		} catch (PDOexception $e) {
			echo "Failed to create database: " . $e->getMessage();
		}
	}

	private function execSqlQuery($query, $param) {
		try {
			$prep = $this->db->prepare($query);
			$prep->execute($param);
			return $prep->fetchAll();
		} catch (PDOexception $e) {
			return [false, $e->getMessage()];
		}
	}

	function createUser($username, $password, $email) {
		$pass = password_hash($password, PASSWORD_BCRYPT);
		return $this->execSqlQuery("INSERT INTO users (username, password, email) VALUES (?, ?, ?)", [$username, $pass, $email]);
	}

	function getUsers(){
		return $this->execSqlQuery("SELECT * FROM users", []);
	}

	function getUser($id) {
		return $this->execSqlQuery("SELECT username FROM users WHERE id = ?", [$id]);
	}

	function getID($username) {
		return $this->execSqlQuery("SELECT id FROM users WHERE username = ?", [$username]);
	}

	function checkPassword($userID, $password){
		$hashedPass = $this->execSqlQuery("SELECT password FROM users WHERE id = ?", [$userID])[0][0];
		return password_verify($password, $hashedPass);
	}

	function checkMailVerif($userID){
		return $this->execSqlQuery("SELECT verified FROM users WHERE id = ?", [$userID]);
	}
}
?>