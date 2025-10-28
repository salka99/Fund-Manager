<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FundModel;
use App\Models\ExpenseModel;

class FundController extends Controller
{
	protected $session;

	public function __construct()
	{
		$this->session = session();
	}

	protected function requireAdmin()
	{
		$user = $this->session->get('user');
		if (!$user || $user['role'] !== 'admin') {
			return false;
		}
		return true;
	}

	public function index()
	{
		return view('funds/index');
	}

	public function list()
	{
		$model = new FundModel();
		$data = $model->getFundWithStats();
		return $this->response->setJSON(['data' => $data]);
	}

	public function store()
	{
		if (!$this->requireAdmin()) {
			return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
		}
		$fundName = trim($this->request->getPost('fund_name'));
		$totalAmount = (float)$this->request->getPost('total_amount');
		$model = new FundModel();
		$id = $model->insert(['fund_name' => $fundName, 'total_amount' => $totalAmount]);
		return $this->response->setJSON(['id' => $id, 'message' => 'Fund created']);
	}

	public function update($id)
	{
		if (!$this->requireAdmin()) {
			return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
		}
		$fundName = trim($this->request->getPost('fund_name'));
		$totalAmount = (float)$this->request->getPost('total_amount');
		$model = new FundModel();
		$model->update($id, ['fund_name' => $fundName, 'total_amount' => $totalAmount]);
		return $this->response->setJSON(['message' => 'Fund updated']);
	}

	public function delete($id)
	{
		if (!$this->requireAdmin()) {
			return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
		}
		$model = new FundModel();
		$model->delete($id);
		return $this->response->setJSON(['message' => 'Fund deleted']);
	}
} 