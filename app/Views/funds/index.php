<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3>Funds</h3>
	<?php $isAdmin = session()->get('user')['role'] === 'admin'; if ($isAdmin): ?>
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fundModal">Add Fund</button>
	<?php endif; ?>
</div>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table id="fundTable" class="table table-striped" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Total</th>
						<th>Spent</th>
						<th>Remaining</th>
						<?php if ($isAdmin): ?><th>Actions</th><?php endif; ?>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>

<?php if ($isAdmin): ?>
<div class="modal fade" id="fundModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add/Edit Fund</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="fundForm">
					<input type="hidden" name="id">
					<div class="mb-3">
						<label class="form-label">Fund Name</label>
						<input type="text" name="fund_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Total Amount</label>
						<input type="number" step="0.01" name="total_amount" class="form-control" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveFund">Save</button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function(){
	const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;
	const fundsListUrl = '<?= site_url('funds/list') ?>';
	const fundsBaseUrl = '<?= site_url('funds') ?>';
	const table = $('#fundTable').DataTable({
		ajax: fundsListUrl,
		columns: [
			{ data: 'id' },
			{ data: 'fund_name' },
			{ data: 'total_amount', render: d => parseFloat(d).toFixed(2) },
			{ data: 'spent_amount', render: d => parseFloat(d).toFixed(2) },
			{ data: 'remaining_amount', render: d => parseFloat(d).toFixed(2) },
			...(isAdmin ? [{ data: null, orderable:false, render: row => `
				<button class="btn btn-sm btn-outline-primary me-1 edit" data-id="${row.id}">Edit</button>
				<button class="btn btn-sm btn-outline-danger delete" data-id="${row.id}">Delete</button>
			`}]: [])
		]
	});

	<?php if ($isAdmin): ?>
	$('#saveFund').on('click', function(){
		const form = $('#fundForm');
		const id = form.find('[name="id"]').val();
		const url = id ? `${fundsBaseUrl}/${id}` : fundsBaseUrl;
		$.post(url, form.serialize()).done(function(){
			const modalEl = document.getElementById('fundModal');
			const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
			modal.hide();
			table.ajax.reload();
		});
	});

	$('#fundTable').on('click', '.edit', function(){
		const id = $(this).data('id');
		const row = table.rows().data().toArray().find(r => r.id == id);
		if (!row) return;
		$('#fundForm')[0].reset();
		$('#fundForm [name="id"]').val(row.id);
		$('#fundForm [name="fund_name"]').val(row.fund_name);
		$('#fundForm [name="total_amount"]').val(row.total_amount);
		const modalEl = document.getElementById('fundModal');
		const modal = new bootstrap.Modal(modalEl);
		modal.show();
	});

	$('#fundTable').on('click', '.delete', function(){
		if (!confirm('Delete this fund?')) return;
		const id = $(this).data('id');
		$.ajax({ url: `${fundsBaseUrl}/${id}`, type: 'DELETE' }).done(function(){
			table.ajax.reload();
		});
	});
	<?php endif; ?>
});
</script>
<?= $this->endSection() ?> 