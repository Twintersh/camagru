<?php
$host = '127.0.0.1:5432';
$dbname = 'camagru';
$user_db = 'twinters';
$pass_db = 'passwaurd';

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname",
		$user_db,
		$pass_db,
		array(
			PDO::ATTR_TIMEOUT => 5,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		)
	);
	// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOexception $e){
	die("Connection failed : " . $e->getMessage());
}

if ($SERVER['REQUEST_METHOD'] == 'POST'){
	$username =	$_POST['username'];
	$password =	$_POST['password'];
	$email =	$_POST['email'];

	if (!empty($username) && !empty($password) && !empty($email)){
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);

		$sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
		$stmt = $conn->prepare($sql);

		try {
			$stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);
			echo "gg bro";
		} catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				echo "Username or email already exists.";
			} else {
				echo "Error: " . $e->getMessage();
			}
		}
	}
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
