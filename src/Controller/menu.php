<?php
	require(__DIR__ . '/config.php');

	$db = new DatabaseManager;
	if (!isset($_SESSION['userId']) || !$_SESSION["userId"]) {
		die("Access denied. Please log in.");
	}
	$mailVerif = $db->checkMailVerif($_SESSION['userId']);
	if (!$mailVerif) {
		die("You were successfully Registered ! Please check your email for verification :)");
	}
	else if (gettype($mailVerif) == "array" && count($mailVerif) == 2) {
		die($mailVerif[1]);
	}
	$db = new DatabaseManager;
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Camagru - Photo sharing application">

	<title>Camagru - Menu</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link rel="stylesheet" href="menu.css">
</head>
<body>
	<nav class="navbar">
		<button class="logo" aria-label="Home">Camagru</button>
		<button class="navbar-button" aria-label="Take photo">📷</button>
		<?php echo $db->getUser($_SESSION["userId"])[0][0] ?>
	</nav>

	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>