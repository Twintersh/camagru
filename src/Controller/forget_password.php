<?php
require(__DIR__ . '/config.php');
require(__DIR__ . '/utils.php');

$db = new DatabaseManager;
$error_message = '';
$success_message = '';

if (isset($_POST["email"])){
	$userID = $db->getIdbyEmail($_POST["email"]);
	if (gettype($userID) != "string"){
		$_SESSION["error_message"] = "This email match with no account.";
		header("location: forget_password.php");
		exit();
	}
	$db->createToken($userID);
	$token = $db->getToken($userID);
	sendMail($_POST["email"], 'gros golem',
	"Reset Password",
	"http://localhost:8000/fieldpassword.php?token=$token");
	$_SESSION["success_message"] = "Email sent ! Check your mail box to change your password";
	header("Location: forget_password.php");
	exit();
}

if (isset($_SESSION['error_message'])) {
	$error_message = $_SESSION['error_message'];
	unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
	$success_message = $_SESSION['success_message'];
	unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camargu - Forget Password</title>
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
				<label for="email">Please enter your email:</label>
				<input type="email" id="email" name="email" placeholder="Email" required>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php elseif ($success_message): ?>
				<p style="color: green;"><?php echo $success_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Reset password</button>
			<div class="form-footer">
				<p>Want to login? <a href="index.php" class="link">Log in</a></p>
			</div>
		</form>
	</div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>
