<!-- <?php
require(__DIR__ . '/config.php');

header('Content-Type: application/json');

$db = new DatabaseManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photo_url'])) {
	$photo_url = $_POST['photo_url'];
	$userID = $_SESSION['userID'] ?? null;

	if ($userID) {
		$newLikeCount = $db->manageLike($photo_url, $userID);
		echo json_encode([
			'success' => true,
			'likes' => $newLikeCount
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
}
?> -->
