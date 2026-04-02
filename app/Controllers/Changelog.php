<?php

namespace App\Controllers;

use App\Models\VersoesModel;
use App\Models\ChangelogModel;

class Changelog extends BaseController
{
    public function index()
    {
        $versoesModel = new VersoesModel();

        return view('changelog/index', [
            'versoes' => $versoesModel->getAll()
        ]);
    }

    public function criarVersao()
    {
        $versoesModel = new VersoesModel();

        $id = $versoesModel->insert([
            'versao' => $this->request->getPost('versao'),
            'descricao' => $this->request->getPost('descricao')
        ]);

        return redirect()->to('/changelog/editar/' . $id);
    }

    public function editar($id)
    {
        $changelogModel = new ChangelogModel();

        return view('changelog/editar', [
            'id_versao' => $id,
            'itens' => $changelogModel->getByVersao($id)
        ]);
    }

    public function adicionarItem()
    {
        $changelogModel = new ChangelogModel();

        $changelogModel->insert([
            'id_versao' => $this->request->getPost('id_versao'),
            'titulo' => $this->request->getPost('titulo'),
            'descricao' => $this->request->getPost('descricao')
            //'ordem' => $this->request->getPost('ordem')
        ]);

        return redirect()->back();
    }
    public function remover($id)
    {
        $changelogModel = new \App\Models\ChangelogModel();
        $versaoModel    = new \App\Models\VersaoModel();

        // remove os itens da versão
        $changelogModel->where('id_versao', $id)->delete();

        // remove a versão
        $versaoModel->delete($id);

        return redirect()->to('/changelog')
            ->with('success', 'Versão e itens removidos com sucesso!');
    }
    
    public function gerarSql($idVersao)
    {
        $versoesModel = new VersoesModel();
        $changelogModel = new ChangelogModel();

        $versao = $versoesModel->find($idVersao);
        $itens = $changelogModel->getByVersao($idVersao);

        $sql = "-- Changelog versão {$versao['versao']}\n\n";

        foreach ($itens as $item) {

            $titulo = addslashes($item['titulo']);
            $descricao = addslashes($item['descricao']);

            $sql .= "INSERT INTO changelog (versao, titulo, descricao) VALUES (\n";
            $sql .= "    '{$versao['versao']}',\n";
            $sql .= "    '{$titulo}',\n";
            $sql .= "    '{$descricao}'\n";
            $sql .= ");\n\n";
        }

        $filename = 'changelog_' . $versao['versao'] . '.sql';

        return $this->response
            ->setHeader('Content-Type', 'text/plain')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($sql);
    }

    public function ordenar()
    {
        $changelogModel = new \App\Models\ChangelogModel();

        $dados = $this->request->getJSON(true);

        foreach ($dados as $item) {
            $changelogModel->update($item['id'], [
                'ordem' => $item['ordem']
            ]);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }
}