<?php
require 'config.php';
$pageTitle = 'Register';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        // check for existing username/email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already taken.';
        }
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hash]);

        $_SESSION['flash'] = 'Registration successful! Please log in.';
        header('Location: login.php');
        exit;
    }
}

require 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Register</h1>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger"><?= h($err) ?></div>
        <?php endforeach; ?>

        <form method="POST" id="registerForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required minlength="3"
                       value="<?= h($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required
                       value="<?= h($_POST['email'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
