<?php

namespace App\Models;

use CodeIgniter\Model;

class VersaoModel extends Model
{
    protected $table = 'versoes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'versao',
        'descricao',
        'data_criacao'
    ];
}