<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;

$message = '';

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] == '')
{
	header("Location: index.php");
	exit();
}

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
			<form class="input-group" action="" method="POST" enctype="multipart/form-data">
				<input type="file" name="image" accept="image/*" required>
				<textarea name="description" placeholder="Add a description... (max 500 characters)"></textarea>
				<button type="submit">Post</button>
			</form>
		</div>
		<!-- <footer class="footer">
			<p>Made by <a href="https://github.com/Twintersh" class="link">twinters</a></p>
		</footer> -->
	</body>
</html>
