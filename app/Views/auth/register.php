<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
	<div class="col-md-5">
		<h3 class="mb-3">Register</h3>
		<?php if (session()->getFlashdata('error')): ?>
			<div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
		<?php endif; ?>
		<form method="post" action="<?= site_url('register') ?>">
			<div class="row g-3">
				<div class="col-12">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="col-12">
					<label class="form-label">Email</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Password</label>
					<input type="password" name="password" class="form-control" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Role</label>
					<select name="role" class="form-select">
						<option value="staff">Staff</option>
						<option value="admin">Admin</option>
					</select>
				</div>
			</div>
			<button class="btn btn-primary mt-3" type="submit">Register</button>
		</form>
	</div>
</div>
<?= $this->endSection() ?> 