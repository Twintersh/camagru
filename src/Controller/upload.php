<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] == '')
{
	header("Location: index.php");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
	$uploadDir = '/var/www/src/View/public/posts/'; // Dossier où l'image sera stockée
	$publicDir = '';
	$maxFileSize = 5 * 1024 * 1024; // Taille maximale du fichier : 5 Mo
	$allowedFileTypes = ['image/jpeg', 'image/png']; // Types d'images autorisés

	$imageName = uniqid() . '_' . basename($_FILES['image']['name']);
	$uploadFile = $uploadDir . $imageName;
	$imageUrl = $publicDir . $imageName;

	if ($_FILES['image']['size'] > $maxFileSize) {
		echo "<p style='color: red;'>L'image est trop grande. La taille maximale autorisée est de 5 Mo.</p>";
	}
	elseif (!in_array($_FILES['image']['type'], $allowedFileTypes)) {
		echo "<p style='color: red;'>Seules les images JPG, PNG et GIF sont autorisées.</p>";
	}

	else {
		if (!is_dir(dirname($uploadFile))) {
			mkdir(dirname($uploadFile), 0777, true);
		}
		if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
			$db->saveImage($_SESSION["userID"], $imageUrl, "");
			echo "<p style='color: green;'>Image téléchargée avec succès !</p>";
		} else {
			echo "<p style='color: red;'>Échec du téléchargement de l'image.</p>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Publication de Photos</title>
	<link rel="stylesheet" href="style/upload.css">
</head>
<body>
	<div class="container">
		<h2>Publier une photo</h2>
		<form action="" method="POST" enctype="multipart/form-data">
			<input type="file" name="image" accept="image/*" required>La photo a été publiée avec succès !
			<textarea name="description" placeholder="Ajouter une description..."></textarea>
			<button type="submit">Publier</button>
		</form>
	</div>
</body>
</html>
