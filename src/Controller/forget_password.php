<?php
require(__DIR__ . '/config.php');
require(__DIR__ . '/utils.php');

$db = new DatabaseManager;

if (isset($_POST["email"])){
	$userID = $db->getIdbyEmail($_POST["email"]);
	// verifier userID
	$chibre = $db->createToken($userID);
	$token = $db->getToken($userID);
	var_dump($chibre);
	var_dump($token);
	sendMail($_POST["email"], 'gros golem',
	"Reset Password",
	"http://localhost:8000/fieldpassword.php?token=$token");
	echo "gg bro wp";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camargu - Forget Password</title>
    <link rel="stylesheet" href="index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div class="login-container">
        <form class="login-form" method="POST">
			<h1 class="txt">Camagru</h1>
            <div class="input-group">
                <label for="email">Please enter your email:</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <button type="submit" class="login-button">Reset password</button>
			<div class="form-footer">
				<p>Want to login? <a href="index.php" class="link">Log in</a></p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>
