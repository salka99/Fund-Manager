<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fund Manager</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="<?= base_url('css/theme.css') ?>" rel="stylesheet">
</head>
<body>
	<?php $currentUser = session()->get('user'); ?>
	<nav class="navbar navbar-expand-lg">
		<div class="container-fluid">
			<a class="navbar-brand" href="<?= site_url($currentUser ? 'dashboard' : '/') ?>">Fund Manager</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<?php if ($currentUser): ?>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('funds') ?>">Funds</a></li>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('expenses') ?>">Expenses</a></li>
					<?php endif; ?>
				</ul>
				<ul class="navbar-nav">
					<?php if ($currentUser): ?>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
					<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Login</a></li>
					<li class="nav-item"><a class="nav-link" href="<?= site_url('register') ?>">Register</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container py-4">
		<?= $this->renderSection('content') ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
	<?= $this->renderSection('scripts') ?>
</body>
</html> 