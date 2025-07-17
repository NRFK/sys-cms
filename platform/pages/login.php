<div class="card text-white bg-dark mb-3">
	<div class="card-header">Login</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<?php if (isset($_SESSION['error'])): ?>
				<div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
				<?php endif; ?>
				<form method="POST" action="/admin/user/login.php">
					<input type="hidden" name="action" value="login">
					<div class="mb-3">
						<label class="form-label">Username</label>
						<input type="text" name="username" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="password" class="form-control" required>
					</div>
					<button class="btn btn-primary w-100">Login</button>
				</form>
			</div>
		</div>
	</div>
</div>