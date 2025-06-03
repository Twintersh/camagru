<?php
require(__DIR__ . '/config.php');

header('Content-Type: application/json');

$db = new DatabaseManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photo_url'])) {
	$photo_url = $_POST['photo_url'];
	$userID = $_SESSION['userID'] ?? null;
	$comment = $_POST['comment'];

	if ($userID) {
		$db->addComment($photo_url, $userID, $comment);
		echo json_encode([
			'success' => true,
			'content' => $db->getComments($photo_url)
		]);
	} else {
		echo json_encode([
			'success' => false,
			'message' => 'Not authenticated.'
		]);
	}
	} else {
		echo json_encode([
			'success' => false,
			'message' => 'Invalid request.'
		]);
	echo "Hell yeah";
}
?>
