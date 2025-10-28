<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FundModel;
use App\Models\ExpenseModel;

class DashboardController extends Controller
{
	public function index()
	{
		$fundModel = new FundModel();
		$expenseModel = new ExpenseModel();

		$funds = $fundModel->getFundWithStats();
		$recent = $expenseModel->getRecent(10);

		return view('dashboard/index', [
			'funds' => $funds,
			'recent' => $recent,
		]);
	}

	public function chartData()
	{
		$fundModel = new FundModel();
		$data = $fundModel->getFundWithStats();
		$labels = array_column($data, 'fund_name');
		$totals = array_map('floatval', array_column($data, 'total_amount'));
		$spents = array_map('floatval', array_column($data, 'spent_amount'));

		return $this->response->setJSON([
			'labels' => $labels,
			'totals' => $totals,
			'spents' => $spents,
		]);
	}
} 