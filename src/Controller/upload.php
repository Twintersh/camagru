<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager;

q
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
			<input type="file" name="image" accept="image/*" required>
			<textarea name="description" placeholder="Ajouter une description..."></textarea>
			<button type="submit">Publier</button>
		</form>
	</div>
</body>
</html>
