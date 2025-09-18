<?php
	require(__DIR__ . '/config.php');

	$db = new DatabaseManager;
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
	let page = 1;
	let loading = false;

	function loadMore() {
		if (loading) return;
		loading = true;
		document.getElementById("loader").style.display = "block";

		fetch("load_more.php?page=" + page)
			.then(response => response.text())
			.then(data => {
			document.getElementById("feed-container").insertAdjacentHTML('beforeend', data);
			page++;
			loading = false;
			document.getElementById("loader").style.display = "none";
			});
	}

	window.addEventListener('scroll', () => {
		if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 10) {
			loadMore();
			window.scrollBy({ top: -15, behavior: "auto" });
		}
	});

	document.addEventListener('DOMContentLoaded', () => {
		const feedContainer = document.getElementById('feed-container') || document;

		feedContainer.addEventListener('click', (e) => {
			// SHOW COMMENTS BUTTON
			const showCommentBtn = e.target.closest('.show-comment-btn');
			if (showCommentBtn) {
				const commentsDiv = showCommentBtn.closest('.interactions').querySelector('.comments');
				commentsDiv.style.display = commentsDiv.style.display === 'none' ? 'block' : 'none';
				return;
			}
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
	<div class="feed-container" id="feed-container">
		<div class="header-feed">
			<h2>Your <span class="color-secondary pacifico">Camagru</span> Feed</h2>
			<p>Discover everyday new post on your camagru feed and let people know about you with posts !</p>
		</div>


		<?php foreach ($pictures as $picture):
			$db = new DatabaseManager();
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
						<button class="show-comment-btn" data-photo="<?= htmlspecialchars($photo_url) ?>">👀 Show Comments</button>
					</div>
					<div class="comment-form" style="display: none;">
						<textarea placeholder="Write your comment..." class="comment-textarea"></textarea>
						<div class="comment-buttons">
							<button class="submit-comment" data-photo="<?= htmlspecialchars($photo_url) ?>" userid="<?= htmlspecialchars($_SESSION["userID"]) ?>">Post</button>
							<button class="cancel-comment">Cancel</button>
						</div>
					</div>
					<div class="comments" style="display: none;">
						<?php
						$commentsList = $db->getComments($photo_url);
						if (empty($commentsList)):
						?>
							<p class="no-comments" > There is no comments yet...</p>

						<?php endif; ?>
						<?php foreach (array_reverse($commentsList) as $comments):
						?>
						<div class='comment-box'>
							<p><strong> @<?=htmlspecialchars($db->getUser($comments["authorid"])[0][0]) ?> </strong></p>
							<p class="comment-content"><?=htmlspecialchars($comments["content"])?></p>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div id="loader" class="loader" style="display: none;">
		Loading...
	</div>
	<footer class="footer">
		<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
	</footer>
</body>
</html>