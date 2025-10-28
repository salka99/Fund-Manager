<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
	protected $table = 'expenses';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $useTimestamps = true;
	protected $allowedFields = ['fund_id', 'description', 'amount', 'spent_by', 'spent_at'];

	public function getRecent(int $limit = 10): array
	{
		return $this->orderBy('spent_at', 'DESC')->findAll($limit);
	}

	public function filterBy(?int $fundId = null, ?string $startDate = null, ?string $endDate = null): array
	{
		$builder = $this->select('expenses.*, funds.fund_name')
			->join('funds', 'funds.id = expenses.fund_id', 'left');

		if ($fundId) {
			$builder->where('expenses.fund_id', $fundId);
		}
		if ($startDate) {
			$builder->where('spent_at >=', $startDate);
		}
		if ($endDate) {
			$builder->where('spent_at <=', $endDate);
		}

		return $builder->orderBy('spent_at', 'DESC')->findAll();
	}
} 