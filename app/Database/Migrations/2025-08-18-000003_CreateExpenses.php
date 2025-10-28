<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpenses extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
			],
			'fund_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			],
			'description' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
			],
			'amount' => [
				'type' => 'DECIMAL',
				'constraint' => '15,2',
				'default' => 0.00,
			],
			'spent_by' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'spent_at' => [
				'type' => 'DATETIME',
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		]);

		$this->forge->addKey('id', true);
		$this->forge->addKey('fund_id');
		$this->forge->addForeignKey('fund_id', 'funds', 'id', 'CASCADE', 'CASCADE');
		$this->forge->createTable('expenses');
	}

	public function down()
	{
		$this->forge->dropTable('expenses');
	}
} 