<?php
require(__DIR__ . '/config.php');

$db = new DatabaseManager();

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$pictures = $db->getPaginatedPictures($offset, $limit);
// var_dump($pictures);
foreach ($pictures as $picture):
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
                    <p class="no-comments">There is no comments yet...</p>
                <?php endif; ?>
                <?php foreach (array_reverse($commentsList) as $comments): ?>
                    <div class='comment-box'>
                        <p><strong>@<?= htmlspecialchars($db->getUser($comments["authorid"])[0][0]) ?></strong></p>
                        <p class="comment-content"><?= htmlspecialchars($comments["content"]) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
