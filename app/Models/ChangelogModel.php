<?php

namespace App\Models;

use CodeIgniter\Model;

class ChangelogModel extends Model
{
    protected $table = 'changelog';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_versao',
        'titulo',
        'descricao',
        'ordem'
    ];

    public function getByVersao($idVersao)
    {
        return $this->where('id_versao', $idVersao)
                    ->orderBy('ordem', 'ASC')
                    ->findAll();
    }
}