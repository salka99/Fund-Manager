<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center justify-content-center" style="min-height: 60vh;">
	<div class="col-lg-8 text-center">
		<h1 class="display-5 mb-3">Welcome to Fund Manager</h1>
		<p class="lead mb-4" style="color: var(--text-muted);">Track funds, monitor expenses, and stay on top of your budget with a clean, modern interface.</p>
		<div class="d-flex gap-3 justify-content-center">
			<a class="btn btn-primary btn-lg" href="<?= site_url('login') ?>">Login</a>
			<a class="btn btn-outline-secondary btn-lg" href="<?= site_url('register') ?>">Create Account</a>
		</div>
		<div class="mt-4" style="color: var(--text-muted);">
			<small>Demo accounts: admin@example.com (admin123), staff@example.com (staff123)</small>
		</div>
	</div>
</div>
<?= $this->endSection() ?> 