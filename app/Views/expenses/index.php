<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3>Expenses</h3>
	<div>
		<a class="btn btn-outline-secondary me-2" id="exportCsv" href="#">Export CSV</a>
		<a class="btn btn-outline-secondary" id="exportPdf" href="#">Export PDF</a>
	</div>
</div>
<div class="card mb-3">
	<div class="card-body">
		<form id="filterForm" class="row g-3 align-items-end">
			<div class="col-md-4">
				<label class="form-label">Fund</label>
				<select name="fund_id" class="form-select">
					<option value="">All</option>
					<?php foreach ($funds as $f): ?>
					<option value="<?= $f['id'] ?>"><?= esc($f['fund_name']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-3">
				<label class="form-label">Start Date</label>
				<input type="date" name="start_date" class="form-control">
			</div>
			<div class="col-md-3">
				<label class="form-label">End Date</label>
				<input type="date" name="end_date" class="form-control">
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-primary w-100">Filter</button>
			</div>
		</form>
	</div>
</div>

<div class="row g-3">
	<div class="col-lg-5">
		<div class="card">
			<div class="card-header">Add Expense</div>
			<div class="card-body">
				<form id="expenseForm">
					<div class="mb-3">
						<label class="form-label">Fund</label>
						<select name="fund_id" class="form-select" required>
							<?php foreach ($funds as $f): ?>
							<option value="<?= $f['id'] ?>"><?= esc($f['fund_name']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<input type="text" name="description" class="form-control" required>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Amount</label>
							<input type="number" step="0.01" name="amount" class="form-control" required>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Spent At</label>
							<input type="datetime-local" name="spent_at" class="form-control" required>
						</div>
					</div>
					<button class="btn btn-primary" type="submit">Add Expense</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header">Expense List</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="expenseTable" class="table table-striped" style="width:100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Fund</th>
								<th>Description</th>
								<th>Amount</th>
								<th>Spent By</th>
								<th>Spent At</th>
								<?php $isAdmin = session()->get('user')['role'] === 'admin'; if ($isAdmin): ?><th>Actions</th><?php endif; ?>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($isAdmin): ?>
<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Expense</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="expenseEditForm">
					<input type="hidden" name="id">
					<div class="mb-3">
						<label class="form-label">Fund</label>
						<select name="fund_id" class="form-select" required>
							<?php foreach ($funds as $f): ?>
							<option value="<?= $f['id'] ?>"><?= esc($f['fund_name']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<input type="text" name="description" class="form-control" required>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Amount</label>
							<input type="number" step="0.01" name="amount" class="form-control" required>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Spent At</label>
							<input type="datetime-local" name="spent_at" class="form-control" required>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label">Spent By</label>
						<input type="text" name="spent_by" class="form-control" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveExpenseEdit">Save</button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function(){
	const expensesListBase = '<?= site_url('expenses/list') ?>';
	const expensesBase = '<?= site_url('expenses') ?>';
	const exportCsvBase = '<?= site_url('expenses/export/csv') ?>';
	const exportPdfBase = '<?= site_url('expenses/export/pdf') ?>';

	function listUrl(){
		const p = $('#filterForm').serialize();
		return expensesListBase + (p ? ('?' + p) : '');
	}
	const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;
	let table = $('#expenseTable').DataTable({
		ajax: listUrl(),
		columns: [
			{ data: 'id' },
			{ data: 'fund_name' },
			{ data: 'description' },
			{ data: 'amount', render: d => parseFloat(d).toFixed(2) },
			{ data: 'spent_by' },
			{ data: 'spent_at' },
			...(isAdmin ? [{ data: null, orderable:false, render: row => `
				<button class="btn btn-sm btn-outline-primary me-1 edit" data-id="${row.id}">Edit</button>
				<button class="btn btn-sm btn-outline-danger delete" data-id="${row.id}">Delete</button>
			`}]: [])
		]
	});

	$('#filterForm').on('submit', function(e){
		e.preventDefault();
		table.ajax.url(listUrl()).load();
	});

	$('#expenseForm').on('submit', function(e){
		e.preventDefault();
		$.post(expensesBase, $(this).serialize()).done(function(){
			$('#expenseForm')[0].reset();
			table.ajax.reload();
		}).fail(function(xhr){
			alert(xhr.responseJSON?.message || 'Failed to add expense');
		});
	});

	$('#expenseTable').on('click', '.delete', function(){
		if (!confirm('Delete this expense?')) return;
		const id = $(this).data('id');
		$.ajax({ url: `${expensesBase}/${id}`, type: 'DELETE' }).done(function(){
			table.ajax.reload();
		});
	});

	$('#expenseTable').on('click', '.edit', function(){
		const id = $(this).data('id');
		const row = table.rows().data().toArray().find(r => r.id == id);
		if (!row) return;
		$('#expenseEditForm')[0].reset();
		$('#expenseEditForm [name="id"]').val(row.id);
		$('#expenseEditForm [name="fund_id"]').val(row.fund_id);
		$('#expenseEditForm [name="description"]').val(row.description);
		$('#expenseEditForm [name="amount"]').val(row.amount);
		// convert 'YYYY-MM-DD HH:MM:SS' to 'YYYY-MM-DDTHH:MM'
		const dt = row.spent_at ? row.spent_at.replace(' ', 'T').slice(0,16) : '';
		$('#expenseEditForm [name="spent_at"]').val(dt);
		$('#expenseEditForm [name="spent_by"]').val(row.spent_by);
		const modalEl = document.getElementById('expenseModal');
		const modal = new bootstrap.Modal(modalEl);
		modal.show();
	});

	$('#saveExpenseEdit').on('click', function(){
		const id = $('#expenseEditForm [name="id"]').val();
		$.post(`${expensesBase}/${id}`, $('#expenseEditForm').serialize()).done(function(){
			const modalEl = document.getElementById('expenseModal');
			const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
			modal.hide();
			table.ajax.reload();
		});
	});

	$('#exportCsv').on('click', function(e){
		e.preventDefault();
		location.href = exportCsvBase + '?' + $('#filterForm').serialize();
	});
	$('#exportPdf').on('click', function(e){
		e.preventDefault();
		location.href = exportPdfBase + '?' + $('#filterForm').serialize();
	});
});
</script>
<?= $this->endSection() ?> 