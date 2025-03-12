<?php
require(__DIR__ . '/config.php');
require(__DIR__ . '/utils.php');
$error_message = '';

function isValidPassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/\d/', $password)) {
        return false;
    }
    return true;
}

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
	elseif ($db->createUser($_POST["username"], $_POST["password"], $_POST["email"])){
		if ($db->getID($_POST["username"])[0][0]){
			$_SESSION["userId"] = $db->getID($_POST["username"])[0][0];
			// sendMail($_POST["email"], $_POST["username"],
			// "object test",
			// "content test");
			header("location: menu.php");
			exit();
		}
		exit();
	}
	header("Location: register.php");
	exit();
}


if (isset($_SESSION['error_message'])) {
	$error_message = $_SESSION['error_message'];
	unset($_SESSION['error_message']); // Clear error after showing it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru - Register</title>
	<link rel="stylesheet" href="index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div class="login-container">
		<form class="login-form" action="register.php" method="POST">
			<h1 class="txt">Camagru</h1>
			<div class="input-group">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Enter your username" required>
			</div>
			<div class="input-group">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" placeholder="Enter your email" required>
			</div>
			<div class="input-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Register</button>
			<div class="form-footer">
				<p>Already have an account? <a href="index.php" class="link">Log in</a></p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>
