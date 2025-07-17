<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
	header("Location: /user/login.php");
	exit;
}
?>
<?php $title = 'NK Login'; ?>
<?php require('../assets/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center mb-4">Register</h3>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <form method="POST" action="/user/secure_func.php">
                <input type="hidden" name="action" value="register">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Register</button>
            </form>
         
        </div>
    </div>
</div>

<?php require('..//assets/footer.php'); ?>