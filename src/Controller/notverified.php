<?php
	require(__DIR__ . '/config.php');
	if (!isset($_SESSION["notverified"])){
		header("location: index.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru - Account not verfied</title>
	<link rel="stylesheet" href="index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossoriginq>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<form class="login-container" action="verifmail.php" method="POST">
			<h1 class='txt'>Camagru</h1>
			<p style="text-align: center;"><?php echo $_SESSION["notverified"]; ?>
			</p>
	</form>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>