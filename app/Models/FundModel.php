<?php

namespace App\Models;

use CodeIgniter\Model;

class FundModel extends Model
{
	protected $table = 'funds';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $useTimestamps = true;
	protected $allowedFields = ['fund_name', 'total_amount'];

	public function getFundWithStats(?int $fundId = null): array
	{
		$db = \Config\Database::connect();
		$builder = $db->table('funds f')
			->select('f.id, f.fund_name, f.total_amount, IFNULL(SUM(e.amount), 0) AS spent_amount, (f.total_amount - IFNULL(SUM(e.amount), 0)) AS remaining_amount')
			->join('expenses e', 'e.fund_id = f.id', 'left')
			->groupBy('f.id');

		if ($fundId !== null) {
			$builder->where('f.id', $fundId);
		}

		$result = $builder->get()->getResultArray();
		return $result;
	}
} 