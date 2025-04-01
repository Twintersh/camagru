<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;
$error_message = '';
$success_message = '';

if (!isset($_GET['token'])){
	header("Location: index.php");
	exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['confirmpassword']) && isset($_POST['password']) && isset($_GET["token"]))
	{
		if ($_POST['confirmpassword'] == $_POST['password']){
			if (!isValidPassword($_POST['password'])){
				$_SESSION["error_message"] = 'The password must be 8 characters long with at least one number.';
				header("Location: fieldpassword.php?token=" . $_GET['token']);
				exit();
			}
			else {
				$db->changePassword($_POST['password'], $_GET["token"]);
				$_SESSION["success_message"] = 'Password had been changed successfuly ! You can now connect.';
				header("Location: index.php");
				exit();
			}
		}
		else {
			$_SESSION['error_message'] = "Passwords don't match.";
			header("Location: fieldpassword.php?token=" . $_GET['token']);
			exit();
		}
	}
}

if (isset($_SESSION['error_message'])) {
	$error_message = $_SESSION['error_message'];
	unset($_SESSION['error_message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camargu - change password</title>
	<link rel="stylesheet" href="style/index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div class="login-container">
		<form class="login-form" method="POST">
			<h1 class="txt">Camagru</h1>
			<div class="input-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required>
			</div>
			<div class="input-group">
				<label for="confirmpassword">Confirm password</label>
				<input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm your password" required>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Change password</button>
			<div class="form-footer">
				<p>Want to <a href="index.php" class="link">Log in</a>?</p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>