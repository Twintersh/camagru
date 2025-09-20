<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;
$error_message = '';
$success_message = '';

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] == '')
{
	header("Location: index.php");
	exit();
}

$username = $db->getUser($_SESSION["userID"])[0]['username'];
$email = $db->getMailFromUsername($username)[0][0];
$notifs = $db->checkNotifStatus($db->getID($username));
$profile = $db->checkProfileStatus($db->getID($username));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$toggleStatusNotif = false;
	$toggleStatusProfile = false;
	if (isset($_POST['toggleSwitch']) && $_POST['toggleSwitch'] == 'on'){
		$toggleStatusNotif = true;
	}
	if (isset($_POST['toggleProfile']) && $_POST['toggleProfile'] == 'on'){
		$toggleStatusProfile = true;
	}
	if (isset($_POST['username']) && $_POST['username'] != $username)
	{
		if (!isValidUsername($_POST["username"])){
			header("Location: userpreferences.php");
			exit();
		}
		$db->changeUsername($_SESSION["userID"], $_POST['username']);
	}
	if (isset($_POST['email']) && $_POST['email'] != $email){
		if (!isValidMail($_POST["email"])){
			header("Location: userpreferences.php");
			exit();
		}
		$db->changeEmail($_SESSION["userID"], $_POST['email']);
	}
	if (isset($_POST['password']) && $_POST['password'] != ''){
		if (!isValidPassword($_POST["password"])){
			$_SESSION["error_message"] = 'The password must be 8 characters long with at least one number.';
			header("Location: userpreferences.php");
			exit();
		}
		$db->changePassword($_POST['password'], $_SESSION["userID"]);
	}
	if ($notifs != $toggleStatusNotif){
		$db->changeNotifStatus($_SESSION["userID"]);
	}
	if ($profile != $toggleStatusProfile){
		$db->changeProfileStatus($_SESSION["userID"]);
	}
	header("Location: menu.php");
	exit();
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
	<link rel="stylesheet" href="style/index.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div class="login-container">
		<form class="login-form" method="POST" action="userpreferences.php">
			<a class="txt" href="menu.php" id="h1">Camagru</a>
			<div class="input-group">
				<label for="username">Change username</label>
				<input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
			</div>
			<div class="input-group">
				<label for="email">Change email</label>
				<input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
			</div>
			<div class="input-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter new password">
			</div>
			<div class="switch-with-text">
				<span class="switch-label">Mail notifications</span>
				<label class="switch">
					<input type="checkbox" id="toggleSwitch"  name="toggleSwitch" <?php if($notifs) echo 'checked'; ?>>
					<span class="slider"></span>
				</label>
			</div>
			<div class="switch-with-text">
				<span class="switch-label">Private profile</span>
				<label class="switch">
					<input type="checkbox" id="toggleProfile"  name="toggleProfile" <?php if($profile) echo 'checked'; ?>>
					<span class="slider"></span>
				</label>
			</div>
			<?php if ($error_message): ?>
				<p style="color: red;"><?php echo $error_message; ?></p>
			<?php elseif ($success_message): ?>
				<p style="color: green;"><?php echo $success_message; ?></p>
			<?php endif; ?>
			<button type="submit" class="login-button">Save changes</button>
		</form>
	</div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>