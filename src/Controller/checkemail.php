<?php
$db = new DatabaseManager;
require(__DIR__ . '/config.php');
// if (isset($_GET['token'])){
	$UserToken = $db->getToken($_SESSION["userId"]);
	if ($UserToken == $_GET['token'])
	{
		header("location: menu.php");
		exit();
	}
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru - Email verification</title>
	<link rel="stylesheet" href="index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossoriginq>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div class="login-container">
			<h1 class='txt'>Camagru</h1>
			<p>You're one step ahead!</p>
			<button type="submit" class="login-button">Verify your email</button>
	</div>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>