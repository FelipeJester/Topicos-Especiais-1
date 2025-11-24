<?php
// views/admin/index.php
include ROOT_PATH . '/views/layouts/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4"><?= $title ?? 'Painel Administrativo' ?></h1>

    <?php if (isset($flash) && $flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gerenciar Carros</h5>
                    <p class="card-text">Adicione, edite ou remova veículos do estoque da concessionária.</p>
                    <a href="<?= BASE_URL ?>carros" class="btn btn-primary">Ver Lista de Carros</a>
                    <a href="<?= BASE_URL ?>admin/carros/create" class="btn btn-success">Adicionar Novo Carro</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Configurações</h5>
                    <p class="card-text">Ajuste as configurações gerais do sistema.</p>
                    <a href="<?= BASE_URL ?>toggle-theme" class="btn btn-secondary">Alternar Tema (Modo Escuro)</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ROOT_PATH . '/views/layouts/footer.php';
?>
