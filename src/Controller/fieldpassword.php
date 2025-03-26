<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['confirmpassword']) && isset($_POST['password']) && isset($_GET["token"])){
		if ($_POST['confirmpassword'] == $_POST['password']){
			if (!isValidPassword($_POST['password'])){
				$_SESSION["error_message"] = 'The password must be 8 characters long with at least one number.';
				header("Location: fieldpassword.php");
				exit();
			}
			else {
				$db->changePassword($_POST['password'], $_GET["token"]);
			}
		}
		else {
			$_SESSION['error_message'] = "Passwords don't match.";
			header("Location: fieldpassword.php");
			exit();
		}
	}
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
	<title>Camargu - change password</title>
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
				<label for="password">Password</label>
				<input type="apassword" id="password" name="password" placeholder="Enter your password" required>
			</div>
			<div class="input-group">
				<label for="confirmpassword">Confirm password</label>
				<input type="apassword" id="confirmpassword" name="confirmpassword" placeholder="Confirm your password" required>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Change password</button>
			<div class="form-footer">
				<p>Don't have an account? <a href="register.php" class="link">Sign up</a></p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by twinters</p>
	</footer>
</body>
</html>