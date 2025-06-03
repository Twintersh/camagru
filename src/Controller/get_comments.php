<?php
require(__DIR__ . '/config.php');

header('Content-Type: application/json');

if (!isset($_GET['photo_url'])) {
    echo json_encode(['error' => 'Photo URL is required']);
    exit();
}

$photo_url = $_GET['photo_url'];
$db = new DatabaseManager();

$comments = $db->getComments($photo_url);
$formatted_comments = [];

foreach ($comments as $comment) {
    $username = $db->getUser($comment['authorid'])[0][0];
    $formatted_comments[] = [
        'author' => $username,
        'content' => $comment['content']
    ];
}

echo json_encode($formatted_comments);