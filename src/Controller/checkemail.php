<?php
require(__DIR__ . '/config.php');

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
	<form class="login-container" action="verifmail.php" method="POST">
			<h1 class='txt'>Camagru</h1>
			<p>You're one step ahead!</p>
			<input name="token" type="text" class="hidden" value="<?php echo isset($_GET["token"]) ? $_GET["token"] : "" ?>"/>
			<button type="submit" class="login-button" name="bouton">Verify your email</button>
	</form>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>