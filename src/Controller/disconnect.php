<?php
	require(__DIR__ . '/config.php');

	$db = new DatabaseManager;
	$_SESSION["userID"] = null;
	header("location: login.php");
	exit();
?>