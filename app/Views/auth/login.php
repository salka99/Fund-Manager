<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
	<div class="col-md-4">
		<h3 class="mb-3">Login</h3>
		<?php if (session()->getFlashdata('error')): ?>
			<div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
		<?php endif; ?>
		<form method="post" action="<?= site_url('login') ?>">
			<div class="mb-3">
				<label class="form-label">Email</label>
				<input type="email" name="email" class="form-control" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Password</label>
				<input type="password" name="password" class="form-control" required>
			</div>
			<button class="btn btn-primary w-100" type="submit">Login</button>
			<div class="mt-3 text-center">
				<a href="<?= site_url('register') ?>">Create an account</a>
			</div>
		</form>
	</div>
</div>
<?= $this->endSection() ?> 