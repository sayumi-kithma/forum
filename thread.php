<?php
require 'config.php';

$threadId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT t.*, u.username, c.name AS category_name, c.id AS category_id
    FROM threads t
    JOIN users u ON u.id = t.user_id
    JOIN categories c ON c.id = t.category_id
    WHERE t.id = ?
");
$stmt->execute([$threadId]);
$thread = $stmt->fetch();

if (!$thread) {
    header('Location: index.php');
    exit;
}

// Handle reply submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        $_SESSION['flash'] = 'Please log in to reply.';
        header('Location: login.php');
        exit;
    }
    $body = trim($_POST['body'] ?? '');
    if ($body === '') {
        $errors[] = 'Reply cannot be empty.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)");
        $stmt->execute([$threadId, $_SESSION['user_id'], $body]);
        header('Location: thread.php?id=' . $threadId . '#replies');
        exit;
    }
}

$pageTitle = $thread['title'];
require 'includes/header.php';

$stmt = $pdo->prepare("
    SELECT p.*, u.username
    FROM posts p
    JOIN users u ON u.id = p.user_id
    WHERE p.thread_id = ?
    ORDER BY p.created_at ASC
");
$stmt->execute([$threadId]);
$posts = $stmt->fetchAll();
?>

<p><a href="category.php?id=<?= (int)$thread['category_id'] ?>">&larr; Back to <?= h($thread['category_name']) ?></a></p>

<div class="card mb-4">
    <div class="card-body">
        <h1 class="card-title"><?= h($thread['title']) ?></h1>
        <h6 class="card-subtitle mb-3 text-muted">by <?= h($thread['username']) ?> on <?= h($thread['created_at']) ?></h6>
        <p class="card-text"><?= nl2br(h($thread['body'])) ?></p>
    </div>
</div>

<h4 id="replies"><?= count($posts) ?> Replies</h4>

<?php foreach ($posts as $p): ?>
    <div class="card mb-2">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted"><?= h($p['username']) ?> &middot; <?= h($p['created_at']) ?></h6>
            <p class="card-text"><?= nl2br(h($p['body'])) ?></p>
        </div>
    </div>
<?php endforeach; ?>

<hr>

<?php if (isLoggedIn()): ?>
    <h5>Post a reply</h5>
    <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?= h($err) ?></div>
    <?php endforeach; ?>
    <form method="POST" id="replyForm" novalidate>
        <div class="mb-3">
            <textarea name="body" rows="4" class="form-control" required placeholder="Write your reply..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Reply</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Log in</a> to post a reply.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
