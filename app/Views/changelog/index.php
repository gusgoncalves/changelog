<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="card p-3 mb-4">
    <h5>Criar nova versão</h5>

    <form method="post" action="/changelog/criar">
        <div class="row">
            <div class="col-md-3">
                <input name="versao" class="form-control" placeholder="1.0.0" required>
            </div>

            <div class="col-md-6">
                <input name="descricao" class="form-control" placeholder="Descrição">
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">Criar</button>
            </div>
        </div>
    </form>
</div>

<div class="card p-3">
    <table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Versão</th>
            <th>Descrição</th>
            <th width="150">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($versoes as $v): ?>
            <tr>
                <td>
                    <strong><?= $v['versao'] ?></strong>
                </td>

                <td>
                    <small class="text-muted"><?= $v['descricao'] ?></small>
                </td>

                <td>
                    <div class="btn-group">
                        <a href="/changelog/editar/<?= $v['id'] ?>" 
                           class="btn btn-sm btn-outline-primary">
                            Editar
                        </a>

                        <a href="/changelog/remover/<?= $v['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Tem certeza que deseja remover?')">
                            Remover
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?= $this->endSection() ?>