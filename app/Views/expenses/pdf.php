<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<style>
		table { width: 100%; border-collapse: collapse; }
		th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
		th { background: #eee; }
	</style>
</head>
<body>
	<h3>Expenses Report</h3>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Fund</th>
				<th>Description</th>
				<th>Amount</th>
				<th>Spent By</th>
				<th>Spent At</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $r): ?>
			<tr>
				<td><?= $r['id'] ?></td>
				<td><?= esc($r['fund_name'] ?? '') ?></td>
				<td><?= esc($r['description']) ?></td>
				<td><?= number_format((float)$r['amount'], 2) ?></td>
				<td><?= esc($r['spent_by']) ?></td>
				<td><?= $r['spent_at'] ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</body>
</html> 