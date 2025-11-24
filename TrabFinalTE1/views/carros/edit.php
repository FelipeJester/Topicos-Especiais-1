<?php
// views/carros/edit.php
include ROOT_PATH . '/views/layouts/header.php';
?>

<div class="container mt-5">
    <h1><?= $title ?? 'Editar Carro' ?></h1>

    <?php if (isset($flash) && $flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
    // Usa os dados antigos (old) se houver erro de validação, senão usa os dados do carro
    $data = $old ?? (array) $carro;
    ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/carros/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control <?= isset($errors['marca']) ? 'is-invalid' : '' ?>" id="marca" name="marca" value="<?= htmlspecialchars($data['marca'] ?? '') ?>" required>
                        <?php if (isset($errors['marca'])): ?>
                            <div class="invalid-feedback"><?= implode('<br>', $errors['marca']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control <?= isset($errors['modelo']) ? 'is-invalid' : '' ?>" id="modelo" name="modelo" value="<?= htmlspecialchars($data['modelo'] ?? '') ?>" required>
                        <?php if (isset($errors['modelo'])): ?>
                            <div class="invalid-feedback"><?= implode('<br>', $errors['modelo']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="ano" class="form-label">Ano</label>
                        <input type="number" class="form-control <?= isset($errors['ano']) ? 'is-invalid' : '' ?>" id="ano" name="ano" value="<?= htmlspecialchars($data['ano'] ?? '') ?>" required min="1900" max="<?= date('Y') + 1 ?>">
                        <?php if (isset($errors['ano'])): ?>
                            <div class="invalid-feedback"><?= implode('<br>', $errors['ano']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" class="form-control <?= isset($errors['cor']) ? 'is-invalid' : '' ?>" id="cor" name="cor" value="<?= htmlspecialchars($data['cor'] ?? '') ?>" required>
                        <?php if (isset($errors['cor'])): ?>
                            <div class="invalid-feedback"><?= implode('<br>', $errors['cor']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="preco" class="form-label">Preço (R$)</label>
                        <input type="number" step="0.01" class="form-control <?= isset($errors['preco']) ? 'is-invalid' : '' ?>" id="preco" name="preco" value="<?= htmlspecialchars($data['preco'] ?? '') ?>" required min="0">
                        <?php if (isset($errors['preco'])): ?>
                            <div class="invalid-feedback"><?= implode('<br>', $errors['preco']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Alterar Foto do Carro (PNG ou JPG)</label>
                    <input class="form-control" type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg">
                    <div class="form-text">Opcional. Tamanho máximo: 10MB.</div>
                </div>

                <button type="submit" class="btn btn-primary">Atualizar Carro</button>
                <a href="<?= BASE_URL ?>admin" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>
</div>

<?php
include ROOT_PATH . '/views/layouts/footer.php';
?>
