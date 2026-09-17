<?php
require 'config.php';
$pageTitle = 'Categories';
require 'includes/header.php';

$stmt = $pdo->query("
    SELECT c.id, c.name, c.description, COUNT(t.id) AS thread_count
    FROM categories c
    LEFT JOIN threads t ON t.category_id = c.id
    GROUP BY c.id
    ORDER BY c.id
");
$categories = $stmt->fetchAll();
?>

<h1 class="mb-4">Discussion Categories</h1>

<div class="list-group">
    <?php foreach ($categories as $cat): ?>
        <a href="category.php?id=<?= (int)$cat['id'] ?>" class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?= h($cat['name']) ?></h5>
                    <p class="mb-1 text-muted"><?= h($cat['description']) ?></p>
                </div>
                <span class="badge bg-primary rounded-pill"><?= (int)$cat['thread_count'] ?> threads</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require 'includes/footer.php'; ?>
