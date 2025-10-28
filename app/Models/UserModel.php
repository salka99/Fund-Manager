<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $useTimestamps = true;
	protected $allowedFields = ['name', 'email', 'password_hash', 'role'];

	public function findByEmail(string $email): ?array
	{
		$user = $this->where('email', $email)->first();
		return $user ?: null;
	}

	public function isAdmin(array $user): bool
	{
		return isset($user['role']) && $user['role'] === 'admin';
	}
} 