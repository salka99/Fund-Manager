<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h3 class="mb-4">Dashboard</h3>
<div class="row g-4">
	<div class="col-12 col-lg-7">
		<div class="card">
			<div class="card-header">Funds Summary</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-striped mb-0">
						<thead>
							<tr>
								<th>Fund</th>
								<th>Total</th>
								<th>Spent</th>
								<th>Remaining</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($funds as $f): ?>
							<tr>
								<td><?= esc($f['fund_name']) ?></td>
								<td><?= number_format((float)$f['total_amount'], 2) ?></td>
								<td><?= number_format((float)$f['spent_amount'], 2) ?></td>
								<td><?= number_format((float)$f['remaining_amount'], 2) ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-5">
		<div class="card">
			<div class="card-header">Fund vs Spent</div>
			<div class="card-body">
				<canvas id="fundChart" height="180"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="card mt-4">
	<div class="card-header">Recent Expenses</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-striped mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Fund ID</th>
						<th>Description</th>
						<th>Amount</th>
						<th>Spent By</th>
						<th>Spent At</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($recent as $r): ?>
					<tr>
						<td><?= $r['id'] ?></td>
						<td><?= $r['fund_id'] ?></td>
						<td><?= esc($r['description']) ?></td>
						<td><?= number_format((float)$r['amount'], 2) ?></td>
						<td><?= esc($r['spent_by']) ?></td>
						<td><?= $r['spent_at'] ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const chartUrl = '<?= site_url('dashboard/chart') ?>';
$(function(){
	$.getJSON(chartUrl, function(res){
		const ctx = document.getElementById('fundChart');
		new Chart(ctx, {
			type: 'bar',
			data: {
				labels: res.labels,
				datasets: [
					{ label: 'Total', backgroundColor: 'rgba(0,0,0,0.7)', borderColor: 'rgba(0,0,0,0.9)', borderWidth: 1, data: res.totals },
					{ label: 'Spent', backgroundColor: 'rgba(0,0,0,0.3)', borderColor: 'rgba(0,0,0,0.5)', borderWidth: 1, data: res.spents },
				]
			},
			options: { responsive: true, maintainAspectRatio: false,
				scales: { x: { ticks: { color: '#eaeaea' } }, y: { ticks: { color: '#eaeaea' } } },
				plugins: { legend: { labels: { color: '#eaeaea' } } }
			}
		});
	});
});
</script>
<?= $this->endSection() ?> 