<?php

namespace App\Models;

use CodeIgniter\Model;

class VersoesModel extends Model
{
    protected $table = 'versoes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'versao',
        'descricao',
        'data_criacao'
    ];

    public function getAll()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }
}
