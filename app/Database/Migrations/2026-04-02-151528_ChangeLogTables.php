<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateChangelogTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'versao' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'data_criacao' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('versoes');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_versao' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ordem' => [
                'type'    => 'INT',
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_versao', 'versoes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('changelog');
    }

    public function down()
    {
        // ordem inversa por causa da FK
        $this->forge->dropTable('changelog', true);
        $this->forge->dropTable('versoes', true);
    }
}