<?php
	require(__DIR__ . '/config.php');

	$db = new DatabaseManager;
	if (!isset($_SESSION['userId']) || !$_SESSION["userId"]) {
		$_SESSION["message"] = 'Access denied. Please <a href="index.php" class="link">log in</a>.';
		header("Location: notverified.php");
		exit();
	}
	$mailVerif = $db->checkMailVerif($_SESSION['userId']);
	if (!$mailVerif) {
		$_SESSION["message"] = "Your account is not verified yet ! Please check your emails";
		header("Location: notverified.php");
		exit();
	}
	else if (gettype($mailVerif) == "array" && count($mailVerif) == 2) {
		die($mailVerif[1]);
	}
	$db = new DatabaseManager;
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Camagru - Photo sharing application">

	<title>Camagru - Menu</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link rel="stylesheet" href="menu.css">
</head>
<body>
	<nav class="navbar">
		<button class="logo" aria-label="Home">Camagru</button>
		<button class="navbar-button" aria-label="Take photo">📷</button>
		<?php echo $db->getUser($_SESSION["userId"])[0][0] ?>
	</nav>
	<div class="bubble bubble-1">
	</div>
	<div class="bubble bubble-2">

	</div>
	<div class="feed-container">
		<div class="header-feed">
			<h2>You <span class="color-secondary pacifico">Camagru</span> Feed</h2>
			<p>Discover everyday new post on your camagru feed and let people know about you with posts !</p>
		</div>
        <div class="post">
			<div class="div-picture">
				<img src="https://i0.wp.com/beyondthebeach.fr//app/uploads/2021/04/balade-privee-en-mer-coucher-de-soleil-5.jpg?fit=960%2C1280&ssl=1" alt="Post Image">
			</div>
            <div class="post-content">
                <p class="username">@user1</p>
                <p>Magnifique coucher de soleil 🌅</p>
            </div>
            <div class="interactions">
                <button class="like-btn">❤️ Like</button>
                <button class="comment-btn">💬 Commenter</button>
            </div>
        </div>

        <div class="post">
		<div class="div-picture">
				<img src="https://www.mmv.fr/images/cms/lac-montagne/lac-blanc-haute-savoie.jpg?frz-v=530" alt="Post Image">
			</div>
            <div class="post-content">
                <p class="username">@user2</p>
                <p>Voyage à la montagne 🏔️</p>
            </div>
            <div class="interactions">
                <button class="like-btn">❤️ Like</button>
                <button class="comment-btn">💬 Commenter</button>
            </div>
        </div>
        <div class="post">
            <img src="https://www.mmv.fr/images/cms/lac-montagne/lac-blanc-haute-savoie.jpg?frz-v=530" alt="Post Image">
            <div class="post-content">
                <p class="username">@user2</p>
                <p>Voyage à la montagne 🏔️</p>
            </div>
            <div class="interactions">
                <button class="like-btn">❤️ Like</button>
                <button class="comment-btn">💬 Commenter</button>
            </div>
        </div>
        <div class="post">
            <img src="https://www.mmv.fr/images/cms/lac-montagne/lac-blanc-haute-savoie.jpg?frz-v=530" alt="Post Image">
            <div class="post-content">
                <p class="username">@user2</p>
                <p>Voyage à la montagne 🏔️</p>
            </div>
            <div class="interactions">
                <button class="like-btn">❤️ Like</button>
                <button class="comment-btn">💬 Commenter</button>
            </div>
        </div>
        <div class="post">
            <img src="https://www.mmv.fr/images/cms/lac-montagne/lac-blanc-haute-savoie.jpg?frz-v=530" alt="Post Image">
            <div class="post-content">
                <p class="username">@user2</p>
                <p>Voyage à la montagne 🏔️</p>
            </div>
            <div class="interactions">
                <button class="like-btn">❤️ Like</button>
                <button class="comment-btn">💬 Commenter</button>
            </div>
        </div>
    </div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>