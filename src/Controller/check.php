<?php
require(__DIR__ . '/config.php');
require(__DIR__ . '/utils.php');

$db = new DatabaseManager;
if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])){
	if (strlen($_POST["username"]) < 5){
		$_SESSION["error_message"] = 'The username must be at least 8 characters long.';
	}
	if (strlen($_POST["username"]) > 12){
		$_SESSION["error_message"] = 'The username can\'t be more than 12 characters long.';
	}
	elseif (preg_match('/[^a-zA-Z0-9]/', $_POST["username"])){
		$_SESSION["error_message"] = 'The username cannot contain special characters.';
	}
	elseif (!isValidPassword($_POST["password"])){
		$_SESSION["error_message"] = 'The password must be 8 characters long with at least one number.';
	}
	elseif ($db->checkEmailExists($_POST["email"])){
		$_SESSION["error_message"] = 'This email is already used.';
	}
	elseif ($db->createUser($_POST["username"], $_POST["password"], $_POST["email"])){
		if ($db->getID($_POST["username"])){
			$_SESSION["userId"] = $db->getID($_POST["username"]);
			if (!$db->createToken($_SESSION["userId"])){
				$_SESSION["error_message"] = 'This email is already used.';
				header("Location: register.php");
				exit();
			}
			$token = $db->getToken($_SESSION["userId"]);
			sendMail($_POST["email"], $_POST["username"],
			"Camagru Verification Mail",
			"http://localhost:8000/checkemail.php?token=$token");
			header("location: menu.php");
			exit();
		}
		exit();
	}
	header("Location: register.php");
	exit();
}


?>