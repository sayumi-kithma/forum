<?php
require 'config.php';
$pageTitle = 'Login';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}

require 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Login</h1>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger"><?= h($err) ?></div>
        <?php endforeach; ?>

        <form method="POST" id="loginForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
