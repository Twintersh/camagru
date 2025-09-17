<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;

$message = '';

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] == '')
{
	header("Location: index.php");
	exit();
}

$usrImages = $db->getUserPictures($_SESSION["userID"]);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']))
{
	$uploadDir = '/var/www/src/View/public/posts/';
	$publicDir = '';
	$allowedFileTypes = ['image/jpeg', 'image/png'];
	$file = $_FILES['image'];

	if ($file['error'] !== UPLOAD_ERR_OK)
	{
		switch ($file['error'])
		{
			case UPLOAD_ERR_FORM_SIZE:
				$_SESSION["upload_message"] = "<p style='color:red;'>L'image dépasse la taille maximale autorisée.</p>";
				header("Location: upload.php");
				exit();
				break;
			case UPLOAD_ERR_PARTIAL:
				$_SESSION["upload_message"] = "<p style='color:red;'>L'image n'a été que partiellement téléchargée.</p>";
				header("Location: upload.php");
				exit();
				break;
			case UPLOAD_ERR_NO_FILE:
				$_SESSION["upload_message"] = "<p style='color:red;'>Aucun fichier n'a été téléchargé.</p>";
				header("Location: upload.php");
				exit();
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$_SESSION["upload_message"] = "<p style='color:red;'>Dossier temporaire manquant sur le serveur.</p>";
				header("Location: upload.php");
				exit();
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$_SESSION["upload_message"] = "<p style='color:red;'>Erreur d'écriture sur le disque.</p>";
				header("Location: upload.php");
				exit();
				break;
			case UPLOAD_ERR_EXTENSION:
				$_SESSION["upload_message"] = "<p style='color:red;'>Téléchargement stoppé par une extension PHP.</p>";
				header("Location: upload.php");
				exit();
				break;
			default:
				$_SESSION["upload_message"] = "<p style='color:red;'>Erreur inconnue lors du téléchargement.</p>";
				header("Location: upload.php");
				exit();
				break;
		}
	}
	else
	{
		$imageName = uniqid() . '_' . basename($file['name']);
		$uploadFile = $uploadDir . $imageName;
		$imageUrl = $publicDir . $imageName;
		if (!in_array($file['type'], $allowedFileTypes))
		{
			$_SESSION["upload_message"] = "<p style='color: red;'>Seules les images JPG et PNG sont autorisées.</p>";
			header("Location: upload.php");
			exit();
		}
		else
		{
			if (!is_dir(dirname($uploadFile)))
			{
				mkdir(dirname($uploadFile), 0777, true);
			}
			if (mb_strlen($_POST['description']) > 511)
			{
				$_SESSION["upload_message"] = "<p style='color: red;'>Description is too long.</p>";
				header("Location: upload.php");
				exit();
			}

			if (move_uploaded_file($file['tmp_name'], $uploadFile))
			{
				$description = isset($_POST['description']) ? $_POST['description'] : '';
				$db->saveImage($_SESSION["userID"], $imageUrl, $description);
				header("Location: menu.php");
				exit();
			}
			else
			{
				$_SESSION["upload_message"] = "<p style='color: red;'>Can't download the image.</p>";
				header("Location: upload.php");
				exit();
			}
		}
	}
}
if (isset($_SESSION['upload_message'])) {
	$message = $_SESSION['upload_message'];
	unset($_SESSION['upload_message']); // Clear error after showing it
}
if (isset($_SESSION['upload_message'])) {
	$message = $_SESSION['upload_message'];
	unset($_SESSION['upload_message']); // Clear error after showing it
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Publication de Photos</title>
		<link rel="stylesheet" href="style/upload.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar">
			<a href="menu.php" class="logo" aria-label="Home">Camagru</a>
		</nav>
		<div class="container">
			<h2>Share a picture</h2>
			<?php if (isset($message)) echo $message; ?>

			<div class="camera-container">
				<button id="startCamera" class="camera-btn">📷 Use Camera</button>
				<div id="videoContainer" class="hidden">
					<div class="video-wrapper">
						<video id="video" autoplay playsinline></video>
						<img id="overlay" class="overlay-image hidden">
					</div>
					<canvas id="canvas" class="hidden"></canvas>
					<div class="overlay-gallery">
						<h3>Choose an overlay:</h3>
						<div class="overlay-options">
							<div class="overlay-option" data-overlay="overlays/frame1.png">
								<img src="overlays/frame1.png" alt="Frame 1">
							</div>
							<div class="overlay-option" data-overlay="overlays/frame2.png">
								<img src="overlays/frame2.png" alt="Frame 2">
							</div>
							<div class="overlay-option" data-overlay="overlays/frame3.png">
								<img src="overlays/frame3.png" alt="Frame 3">
							</div>
							<div class="overlay-option" data-overlay="overlays/frame4.png">
								<img src="overlays/frame4.png" alt="Frame 4">
							</div>
						</div>
					</div>
					<div class="camera-controls">
						<button id="captureBtn" class="capture-btn">Take Photo</button>
						<button id="retakeBtn" class="retake-btn hidden">Retake</button>
					</div>
				</div>
				<p id="cameraError" class="error-message hidden"></p>
			</div>

			<form class="input-group" action="" method="POST" enctype="multipart/form-data">
				<input type="file" name="image" accept="image/*" required>
				<textarea name="description" placeholder="Add a description... (max 500 characters)"></textarea>
				<button type="submit">Post</button>
			</form>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', () => {
				const startCameraBtn = document.getElementById('startCamera');
				const videoContainer = document.getElementById('videoContainer');
				const video = document.getElementById('video');
				const canvas = document.getElementById('canvas');
				const captureBtn = document.getElementById('captureBtn');
				const retakeBtn = document.getElementById('retakeBtn');
				const cameraError = document.getElementById('cameraError');
				const fileInput = document.querySelector('input[type="file"]');
				const overlayImg = document.getElementById('overlay');
				const overlayOptions = document.querySelectorAll('.overlay-option');
				let currentStream = null;
				let selectedOverlay = null;

				async function startCamera() {
					try {
						const constraints = {
							video: {
								facingMode: 'user',
								width: { ideal: 1280 },
								height: { ideal: 720 }
							}
						};

						const stream = await navigator.mediaDevices.getUserMedia(constraints);
						video.srcObject = stream;
						currentStream = stream;
						videoContainer.classList.remove('hidden');
						startCameraBtn.classList.add('hidden');
						cameraError.classList.add('hidden');
					} catch (err) {
						console.error('Error accessing camera:', err);
						cameraError.textContent = 'Unable to access camera. Please make sure you have granted camera permissions.';
						cameraError.classList.remove('hidden');
					}
				}

				function capturePhoto() {
					canvas.width = video.videoWidth;
					canvas.height = video.videoHeight;
					const ctx = canvas.getContext('2d');

					// Draw the video frame
					ctx.drawImage(video, 0, 0);

					// If an overlay is selected, draw it
					if (selectedOverlay) {
						ctx.drawImage(overlayImg, 0, 0, canvas.width, canvas.height);
					}

					canvas.toBlob((blob) => {
						const file = new File([blob], 'camera-photo.jpg', { type: 'image/jpeg' });
						const dataTransfer = new DataTransfer();
						dataTransfer.items.add(file);
						fileInput.files = dataTransfer.files;
					}, 'image/jpeg');

					// Show retake button and hide capture button
					captureBtn.classList.add('hidden');
					retakeBtn.classList.remove('hidden');
				}

				function retakePhoto() {
					// Show capture button and hide retake button
					captureBtn.classList.remove('hidden');
					retakeBtn.classList.add('hidden');
				}

				// Handle overlay selection
				overlayOptions.forEach(option => {
					option.addEventListener('click', () => {
						// Remove selected class from all options
						overlayOptions.forEach(opt => opt.classList.remove('selected'));
						// Add selected class to clicked option
						option.classList.add('selected');
						// Set the overlay image
						selectedOverlay = option.dataset.overlay;
						overlayImg.src = selectedOverlay;
						overlayImg.classList.remove('hidden');
					});
				});

				startCameraBtn.addEventListener('click', startCamera);
				captureBtn.addEventListener('click', capturePhoto);
				retakeBtn.addEventListener('click', retakePhoto);

				const sidebar = document.getElementById("sidebar");
				const handle = document.getElementById("resize-handle");

				let isResizing = false;



				handle.addEventListener("mousedown", (e) => {
					if (e.button != 0) return;
					isResizing = true;
					document.body.style.cursor = "ew-resize";
					document.body.style.userSelect = "none"
				});

				window.addEventListener("mousemove", (e) => {
					handle.style.left = window.innerWidth - sidebar.offsetWidth + "px";
					if (!isResizing) return;

					const newWidth = window.innerWidth - e.clientX;
					if (newWidth > 150 && newWidth < 600) { // min/max width
						sidebar.style.width = newWidth + "px";
						handle.style.left = e.clientX + 'px';
					}
				});

				window.addEventListener("mouseup", () => {
					isResizing = false;
					document.body.style.cursor = "default";
					document.body.style.userSelect = ""
				});

				document.querySelectorAll(".delete").forEach(btn => {
					btn.addEventListener("click", () => {
					const photoUrl = btn.getAttribute('data-photo');
					fetch('delete.php', {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: `photo_url=${encodeURIComponent(photoUrl)}`
					})
					.then((res) => res.json())
					.then((data) => {
						alert(data.message);
						btn.parentElement.remove();
					});
					});
				});

			});

		</script>
		<div class="sidebar" id="sidebar">
			<div class="resize-handle" id="resize-handle"></div>
			<?php foreach ($usrImages as $img): ?>
				<div class="box" id="box">
					<img src="image.php?file=<?= htmlspecialchars($img["photo_url"]) ?>" alt="Sidebar Image">
					<span class="delete" id="deleteBtn" data-photo="<?= htmlspecialchars($img["photo_url"]) ?>" >&times;</span>
				</div>
			<?php endforeach; ?>
		</div>
		<footer class="footer">
			<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
		</footer>
		<a href="disconnect.php" class="fixed-btn">Disconnect</a>
	</body>
</html>
