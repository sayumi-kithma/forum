<?php
require 'config.php';

if (!isLoggedIn()) {
    $_SESSION['flash'] = 'Please log in to start a thread.';
    header('Location: login.php');
    exit;
}

$categoryId = (int)($_GET['category_id'] ?? $_POST['category_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');

    if ($title === '' || $body === '') {
        $errors[] = 'Both title and message are required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO threads (category_id, user_id, title, body) VALUES (?, ?, ?, ?)");
        $stmt->execute([$categoryId, $_SESSION['user_id'], $title, $body]);
        $newId = $pdo->lastInsertId();
        header('Location: thread.php?id=' . $newId);
        exit;
    }
}

$pageTitle = 'New Thread';
require 'includes/header.php';
?>

<h1 class="mb-4">New Thread in <?= h($category['name']) ?></h1>

<?php foreach ($errors as $err): ?>
    <div class="alert alert-danger"><?= h($err) ?></div>
<?php endforeach; ?>

<form method="POST" id="threadForm" novalidate>
    <input type="hidden" name="category_id" value="<?= (int)$categoryId ?>">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required
               value="<?= h($_POST['title'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="body" rows="6" class="form-control" required><?= h($_POST['body'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Post Thread</button>
    <a href="category.php?id=<?= (int)$categoryId ?>" class="btn btn-outline-secondary">Cancel</a>
</form>

<?php require 'includes/footer.php'; ?>
