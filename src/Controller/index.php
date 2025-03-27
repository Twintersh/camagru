<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;
$error_message = '';
$success_message = '';

if (isset($_SESSION["userId"]))
{
	header("Location: menu.php");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['username']) && isset($_POST['password'])){
		$userID = $db->getID($_POST['username']);
		if ($userID){
			if ($db->checkPassword($userID, $_POST['password'])){
				$_SESSION["userId"] = $userID;
				header("Location: menu.php");
				exit();
			}
			else {
				$_SESSION['error_message'] = "Sorry, your password is not correct. Please retry.";
				header("Location: index.php");
				exit();
			}
		}
		else {
			$_SESSION['error_message'] = "Sorry, this username do not exist. Please retry or register.";
			header("Location: index.php");
			exit();
		}
	}
}

if (isset($_SESSION['error_message'])) {
	$error_message = $_SESSION['error_message'];
	unset($_SESSION['error_message']); // Clear error after showing it
}
if (isset($_SESSION['success_message'])) {
	$success_message = $_SESSION['success_message'];
	unset($_SESSION['success_message']); // Clear error after showing it
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camargu - Login</title>
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
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Enter your username" required>
			</div>
			<div class="input-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php elseif ($success_message): ?>
				<p style="color: green;"><?php echo $success_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Login</button>
			<div class="form-footer">
				<a href="forget_password.php" class="link">Forgot your password?</a>
				<p>Don't have an account? <a href="register.php" class="link">Sign up</a></p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>