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
		$userID = $this->execSqlQuery("SELECT id FROM users WHERE username = ?", [$username]);
		if (count($userID) > 0 && count($userID[0]) > 0 && isset($userID[0]['id'])){
			return $userID[0]['id'];
		}
		return $userID;
	}

	function getIdbyEmail($email){
		$userID = $this->execSqlQuery("SELECT id FROM users WHERE email = ?", [$email]);
		if (count($userID) > 0 && count($userID[0]) > 0 && isset($userID[0]['id'])){
			return $userID[0]['id'];
		}
		return $userID;
	}

	function checkPassword($userID, $password){
		$hashedPass = $this->execSqlQuery("SELECT password FROM users WHERE id = ?", [$userID])[0][0];

		return password_verify($password, $hashedPass);
	}

	function checkMailVerif($userID){
		$verified = $this->execSqlQuery("SELECT verified FROM tokens WHERE userID = ?", [$userID]);
		if (gettype($verified) == "array" && count($verified) > 0 && gettype($verified[0]) == "array" && count($verified[0]) > 0 && isset($verified[0]['verified'])){
			return $verified[0]['verified'];
		}
		return $verified;
	}

	function createToken($userID){
		$usertoken = $this->execSqlQuery("SELECT * FROM tokens WHERE userID = ?", [$userID]);
		if (is_array($usertoken) && count($usertoken) > 0 && gettype($usertoken[0]) == 'boolean' && $usertoken[0] == false) {
			return $usertoken;
		}
		$userverifiedTokens = $this->execSqlQuery("SELECT * FROM tokens WHERE userID = ? AND verified = TRUE", [$userID]);
		if (count($usertoken) > 0 && count($usertoken) != count($userverifiedTokens)){
			foreach ($usertoken as $token) {
				if (!$token['verified']) {
					return $token;
				}
			}
		}
		return $this->execSqlQuery("INSERT INTO tokens (userID) VALUES (?)", [$userID]);

	}

	function getToken($userID){
		$tokens = $this->execSqlQuery("SELECT * FROM tokens WHERE userID = ?", [$userID]);
		if (!is_array($tokens) || count($tokens) === 0) {
			return false;
		}
		foreach ($tokens as $token) {
			if (!$token['verified']) {
				return $token['veriftoken'];
			}
		}
		return false;
	}

	function verifAccount($token){
		$data = $this->execSqlQuery("UPDATE tokens SET verified = TRUE WHERE verifToken = ?", [$token]);
		$userID  = $this->execSqlQuery("SELECT userID FROM tokens WHERE verifToken = ?", [$token]);
		if (count($userID) > 0 && count($userID[0]) > 0 && isset($userID[0]['userid'])){
			return $userID[0]['userid'];
		}
		return $userID;
	}

	function checkEmailExists($email){
		$data = $this->execSqlQuery("SELECT email FROM users WHERE email = ?", [$email]);
		if (count($data) > 0){
			return true;
		}
		return false;
	}

	function changePassword($password, $token){
		$userId = $this->execSqlQuery("SELECT userID from tokens WHERE verifToken = ?", [$token]);
		$hashedPass = password_hash($password, PASSWORD_BCRYPT);
		$data = $this->execSqlQuery("UPDATE users SET password = ? WHERE id = ?", [$hashedPass, $userId[0]["userid"]]);
		return $data;
	}

	function saveImage($userID, $imagePath, $description){
		$this->execSqlQuery("INSERT INTO picure (authorID, photo_url, description) VALUES (?, ?, ?)", [$userID, $imagePath, $description]);
	}

	function getLastImageSaved(){
		$photo_url = $this->execSqlQuery("SELECT photo_url FROM pictures ORDER BY created_at DESC LIMIT 1", []);
		if (!gettype($photo_url) == "array" && count($photo_url) > 0 && count($photo_url[0]) > 0 && isset($photo_url[0]['photo_url'])){
			return $photo_url[0]['photo_url'];
		}
		return $photo_url;
	}
}
?>