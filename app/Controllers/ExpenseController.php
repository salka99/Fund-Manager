<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ExpenseModel;
use App\Models\FundModel;

class ExpenseController extends Controller
{
	protected $session;

	public function __construct()
	{
		$this->session = session();
	}

	protected function currentUser()
	{
		return $this->session->get('user');
	}

	protected function isAdmin(): bool
	{
		$user = $this->currentUser();
		return $user && $user['role'] === 'admin';
	}

	public function index()
	{
		$funds = (new FundModel())->findAll();
		return view('expenses/index', ['funds' => $funds]);
	}

	public function list()
	{
		$fundId = $this->request->getGet('fund_id');
		$start = $this->request->getGet('start_date');
		$end = $this->request->getGet('end_date');
		if ($end) {
			$end .= ' 23:59:59';
		}
		$model = new ExpenseModel();
		$data = $model->filterBy($fundId ? (int)$fundId : null, $start ?: null, $end ?: null);
		return $this->response->setJSON(['data' => $data]);
	}

	public function store()
	{
		$user = $this->currentUser();
		if (!$user) {
			return $this->response->setStatusCode(401)->setJSON(['message' => 'Unauthorized']);
		}

		$fundId = (int)$this->request->getPost('fund_id');
		$description = trim($this->request->getPost('description'));
		$amount = (float)$this->request->getPost('amount');
		$spentAt = (string)$this->request->getPost('spent_at');
		$spentAt = $spentAt ? str_replace('T', ' ', substr($spentAt, 0, 19)) : null;

		// Validate remaining balance
		$fundModel = new FundModel();
		$stats = $fundModel->getFundWithStats($fundId);
		if (!$stats) {
			return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid fund']);
		}
		$remaining = (float)$stats[0]['remaining_amount'];
		if ($amount > $remaining) {
			return $this->response->setStatusCode(400)->setJSON(['message' => 'Amount exceeds remaining balance']);
		}

		$model = new ExpenseModel();
		$id = $model->insert([
			'fund_id' => $fundId,
			'description' => $description,
			'amount' => $amount,
			'spent_by' => $user['name'],
			'spent_at' => $spentAt,
		]);

		return $this->response->setJSON(['id' => $id, 'message' => 'Expense added']);
	}

	public function update($id)
	{
		if (!$this->isAdmin()) {
			return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
		}

		$spentAt = (string)$this->request->getPost('spent_at');
		$spentAt = $spentAt ? str_replace('T', ' ', substr($spentAt, 0, 19)) : null;
		$data = [
			'fund_id' => (int)$this->request->getPost('fund_id'),
			'description' => trim($this->request->getPost('description')),
			'amount' => (float)$this->request->getPost('amount'),
			'spent_by' => trim($this->request->getPost('spent_by')),
			'spent_at' => $spentAt,
		];

		$model = new ExpenseModel();
		$model->update($id, $data);
		return $this->response->setJSON(['message' => 'Expense updated']);
	}

	public function delete($id)
	{
		if (!$this->isAdmin()) {
			return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
		}
		$model = new ExpenseModel();
		$model->delete($id);
		return $this->response->setJSON(['message' => 'Expense deleted']);
	}

	public function exportCsv()
	{
		$fundId = $this->request->getGet('fund_id');
		$start = $this->request->getGet('start_date');
		$end = $this->request->getGet('end_date');
		if ($end) {
			$end .= ' 23:59:59';
		}
		$model = new ExpenseModel();
		$rows = $model->filterBy($fundId ? (int)$fundId : null, $start ?: null, $end ?: null);

		$filename = 'expenses_' . date('Ymd_His') . '.csv';
		$headers = ['ID', 'Fund', 'Description', 'Amount', 'Spent By', 'Spent At'];

		$csv = fopen('php://temp', 'r+');
		fputcsv($csv, $headers);
		foreach ($rows as $r) {
			fputcsv($csv, [
				$r['id'], $r['fund_name'] ?? '', $r['description'], $r['amount'], $r['spent_by'], $r['spent_at']
			]);
		}
		rewind($csv);
		$content = stream_get_contents($csv);
		fclose($csv);

		return $this->response
			->setHeader('Content-Type', 'text/csv')
			->setHeader('Content-Disposition', 'attachment; filename=' . $filename)
			->setBody($content);
	}

	public function exportPdf()
	{
		$fundId = $this->request->getGet('fund_id');
		$start = $this->request->getGet('start_date');
		$end = $this->request->getGet('end_date');
		if ($end) {
			$end .= ' 23:59:59';
		}
		$model = new ExpenseModel();
		$rows = $model->filterBy($fundId ? (int)$fundId : null, $start ?: null, $end ?: null);

		if (!class_exists('Dompdf\\Dompdf')) {
			return $this->response->setStatusCode(500)->setBody('PDF export requires dompdf/dompdf. Run composer require dompdf/dompdf');
		}

		$html = view('expenses/pdf', ['rows' => $rows]);

		$dompdf = new \Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream('expenses_' . date('Ymd_His') . '.pdf');
		return; // dompdf handles output
	}
} 