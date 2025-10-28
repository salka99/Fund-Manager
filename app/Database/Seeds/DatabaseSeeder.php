<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\FundModel;
use App\Models\ExpenseModel;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$users = new UserModel();
		if (!$users->findByEmail('admin@example.com')) {
			$users->insert([
				'name' => 'Admin',
				'email' => 'admin@example.com',
				'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
				'role' => 'admin',
			]);
		}
		if (!$users->findByEmail('staff@example.com')) {
			$users->insert([
				'name' => 'Staff',
				'email' => 'staff@example.com',
				'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
				'role' => 'staff',
			]);
		}

		$funds = new FundModel();
		if ($funds->countAll() === 0) {
			$marketingId = $funds->insert(['fund_name' => 'Marketing', 'total_amount' => 5000]);
			$operationsId = $funds->insert(['fund_name' => 'Operations', 'total_amount' => 10000]);
			$hrId = $funds->insert(['fund_name' => 'HR', 'total_amount' => 3000]);

			$expenses = new ExpenseModel();
			$expenses->insertBatch([
				['fund_id' => $marketingId, 'description' => 'Social Ads', 'amount' => 500, 'spent_by' => 'Admin', 'spent_at' => date('Y-m-d 10:00:00')],
				['fund_id' => $operationsId, 'description' => 'Office Supplies', 'amount' => 200, 'spent_by' => 'Staff', 'spent_at' => date('Y-m-d 11:00:00')],
				['fund_id' => $hrId, 'description' => 'Training', 'amount' => 300, 'spent_by' => 'Admin', 'spent_at' => date('Y-m-d 12:00:00')],
			]);
		}
	}
} 