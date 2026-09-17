<?php
require 'config.php';

$categoryId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: index.php');
    exit;
}

$pageTitle = $category['name'];
require 'includes/header.php';

$stmt = $pdo->prepare("
    SELECT t.id, t.title, t.created_at, u.username,
           (SELECT COUNT(*) FROM posts p WHERE p.thread_id = t.id) AS reply_count
    FROM threads t
    JOIN users u ON u.id = t.user_id
    WHERE t.category_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$categoryId]);
$threads = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?= h($category['name']) ?></h1>
        <p class="text-muted"><?= h($category['description']) ?></p>
    </div>
    <?php if (isLoggedIn()): ?>
        <a href="new_thread.php?category_id=<?= (int)$categoryId ?>" class="btn btn-success">+ New Thread</a>
    <?php endif; ?>
</div>

<?php if (empty($threads)): ?>
    <p class="text-muted">No threads yet. <?= isLoggedIn() ? 'Be the first to start one!' : 'Log in to start one!' ?></p>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($threads as $t): ?>
            <a href="thread.php?id=<?= (int)$t['id'] ?>" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1"><?= h($t['title']) ?></h5>
                        <small class="text-muted">by <?= h($t['username']) ?> on <?= h($t['created_at']) ?></small>
                    </div>
                    <span class="badge bg-secondary rounded-pill align-self-center"><?= (int)$t['reply_count'] ?> replies</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p class="mt-4"><a href="index.php">&larr; Back to categories</a></p>

<?php require 'includes/footer.php'; ?>
