<?php
require(__DIR__ . '/config.php');
$db = new DatabaseManager;
if (isset($_POST["bouton"]) && isset($_POST['token'])){
		$userID = $db->verifAccount($_POST['token']);
		$_SESSION["userID"] = $userID;
		header("location: menu.php");
		exit();
}

?>