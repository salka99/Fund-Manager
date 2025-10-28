<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunds extends Migration
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
			'fund_name' => [
				'type' => 'VARCHAR',
				'constraint' => 150,
			],
			'total_amount' => [
				'type' => 'DECIMAL',
				'constraint' => '15,2',
				'default' => 0.00,
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
		$this->forge->addUniqueKey('fund_name');
		$this->forge->createTable('funds');
	}

	public function down()
	{
		$this->forge->dropTable('funds');
	}
} 