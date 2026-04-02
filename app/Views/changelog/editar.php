<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Itens do Changelog</h3>

    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalItem">
            + Novo Item
        </button>

        <a href="/changelog/sql/<?= $id_versao ?>" class="btn btn-success">
            Gerar SQL
        </a>
        <a href="/changelog" class="btn btn-warning">
            Voltar
        </a>
    </div>
</div>

<div id="listaItens" class="list-group">
    <?php foreach ($itens as $item): ?>
        <div class="list-group-item item-card" data-id="<?= $item['id'] ?>">
            <strong><?= $item['titulo'] ?></strong>
            <p class="mb-0"><?= $item['descricao'] ?></p>
        </div>
    <?php endforeach; ?>
</div>
<div class="modal fade" id="modalItem">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="/changelog/add-item">
                <div class="modal-header">
                    <h5>Novo Item</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_versao" value="<?= $id_versao ?>">

                    <div class="mb-2">
                        <label>Título</label>
                        <input name="titulo" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Descrição</label>
                        <textarea name="descricao" class="form-control" rows="4"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Salvar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
const lista = document.getElementById('listaItens');

new Sortable(lista, {
    animation: 150,
    onEnd: function () {
        let ordem = [];

        document.querySelectorAll('#listaItens .item-card').forEach((el, index) => {
            ordem.push({
                id: el.dataset.id,
                ordem: index
            });
        });

        fetch('/changelog/ordenar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ordem)
        });
    }
});
</script>

<?= $this->endSection() ?>