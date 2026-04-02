<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Changelog Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .item-card {
            cursor: grab;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>