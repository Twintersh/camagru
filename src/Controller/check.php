<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;
if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])){
	if (!isValidUsername($_POST["username"])){
		header("Location: register.php");
		exit();
	}
	elseif (!isValidPassword($_POST["password"])){
		$_SESSION["error_message"] = 'The password must be 8 characters long with at least one number.';
	}
	elseif ($db->checkEmailExists($_POST["email"])){
		$_SESSION["error_message"] = 'This email is already used.';
	}
	elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		$_SESSION["error_message"] = 'Invalid email format.';
	elseif ($db->createUser($_POST["username"], $_POST["password"], $_POST["email"])){
		if ($db->getID($_POST["username"])){
			$_SESSION["userID"] = $db->getID($_POST["username"]);
			if (!$db->createToken($_SESSION["userID"])){
				$_SESSION["error_message"] = 'This email is already used.';
				header("Location: register.php");
				exit();
			}
			$token = $db->getToken($_SESSION["userID"]);
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