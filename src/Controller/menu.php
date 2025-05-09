<?php
	require(__DIR__ . '/config.php');

	$db = new DatabaseManager;
	if (!isset($_SESSION["userID"]) || !$_SESSION["userID"]) {
		$_SESSION["message"] = 'Access denied. Please <a href="index.php" class="link">log in</a>.';
		header("Location: notverified.php");
		exit();
	}
	$mailVerif = $db->checkMailVerif($_SESSION["userID"]);
	if (!$mailVerif) {
		$_SESSION["message"] = "Your account is not verified yet ! Please check your emails";
		header("Location: notverified.php");
		exit();
	}
	else if (gettype($mailVerif) == "array" && count($mailVerif) == 2) {
		die($mailVerif[1]);
	}
	// echo $db->getLastImageSaved();

	$pictures = $db->getLastPictures();
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
	<link rel="stylesheet" href="style/menu.css">
</head>
<script>
	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.like-btn').forEach(button => {
			button.addEventListener('click', () => {
				const photoUrl = button.getAttribute('data-photo');

				fetch('like.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: `photo_url=${encodeURIComponent(photoUrl)}`
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						button.querySelector('.like-count').textContent = data.likes;
					} else {
						alert(data.message);
					}
				});
			});
		});

		document.querySelectorAll('.comment-btn').forEach(button => {
			button.addEventListener('click', () => {
				const commentForm = button.closest('.interactions').querySelector('.comment-form');
				commentForm.style.display = commentForm.style.display === 'none' ? 'block' : 'none';
			});
		});

		document.querySelectorAll('.submit-comment').forEach(button => {
			button.addEventListener('click', () => {
				const photoUrl = button.getAttribute('data-photo');
				const userid = button.getAttribute('userid');
				const commentText = button.parentElement.previousElementSibling.value;

				if (!commentText.trim()) {
					alert('Please write a comment first!');
					return;
				}

				fetch('comment.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: `photo_url=${encodeURIComponent(photoUrl)}&comment=${encodeURIComponent(commentText)}&userID=${encodeURIComponent()}`
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						button.parentElement.previousElementSibling.value = '';
						button.parentElement.parentElement.style.display = 'none';
						alert('Comment posted successfully!');
					} else {
						alert(data.message || 'Error posting comment');
					}
				})
				.catch(error => {
					alert('Error posting comment');
				});
			});
		});

		document.querySelectorAll('.cancel-comment').forEach(button => {
			button.addEventListener('click', () => {
				const commentForm = button.closest('.comment-form');
				commentForm.querySelector('.comment-textarea').value = '';
				commentForm.style.display = 'none';
			});
		});
	});
</script>
<body>
	<nav class="navbar">
		<button class="logo" aria-label="Home">Camagru</button>
		<a href="upload.php" class="navbar-button" aria-label="Take photo">📷</a>
	</nav>
	<div class="bubble bubble-1">
	</div>
	<div class="bubble bubble-2">

	</div>
	<div class="feed-container">
		<div class="header-feed">
			<h2>Your <span class="color-secondary pacifico">Camagru</span> Feed</h2>
			<p>Discover everyday new post on your camagru feed and let people know about you with posts !</p>
		</div>


		<?php foreach ($pictures as $picture):
			$photo_url = $picture['photo_url'];
			$author = $db->getAuthorFromPhotoUrl($photo_url);
			$desc = $db->getDescriptionFromPhotoUrl($photo_url);
			$nblikes = $db->getLikesNb($photo_url);
			?>
			<div class="post">
				<div class="div-picture">
					<img src="image.php?file=<?= htmlspecialchars($photo_url) ?>" alt="Post Image">
				</div>
				<div class="post-content">
					<p class="username">@<?= htmlspecialchars($author) ?></p>
					<p><?= htmlspecialchars($desc) ?></p>
				</div>
				<div class="interactions">
					<div class="button-container">
						<button type="button" class="like-btn" data-photo="<?= htmlspecialchars($photo_url) ?>">
							❤️ <span class="like-count"><?= htmlspecialchars($nblikes) ?></span>
						</button>
						<button class="comment-btn" data-photo="<?= htmlspecialchars($photo_url) ?>">💬 Comment</button>
					</div>
					<div class="comment-form" style="display: none;">
						<textarea placeholder="Write your comment..." class="comment-textarea"></textarea>
						<div class="comment-buttons">
							<button class="submit-comment" data-photo="<?= htmlspecialchars($photo_url) ?>" userid="<?= htmlspecialchars($_SESSION["userID"]) ?>">Post</button>
							<button class="cancel-comment">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>