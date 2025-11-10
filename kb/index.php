<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Buscar todas as categorias que têm artigos
$sql_categorias = "SELECT c.id_categoria, c.nome_categoria
                   FROM categorias c
                   JOIN base_conhecimento kb ON c.id_categoria = kb.id_categoria_fk
                   GROUP BY c.id_categoria
                   ORDER BY c.nome_categoria";
$stmt_cat = $pdo->query($sql_categorias);
$categorias = $stmt_cat->fetchAll();

// 3. Buscar todos os artigos
$sql_artigos = "SELECT * FROM base_conhecimento ORDER BY titulo";
$stmt_artigos = $pdo->query($sql_artigos);
$artigos = $stmt_artigos->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Base de Conhecimento (KB)</h2>
    <a href="novo.php" class="btn btn-primary">Novo Artigo</a>
</div>
<p>Encontre soluções e procedimentos padrão.</p>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['status'] == 'sucesso') echo 'Artigo criado com sucesso!';
        if ($_GET['status'] == 'editado') echo 'Artigo atualizado com sucesso!';
        if ($_GET['status'] == 'excluido') echo 'Artigo excluído com sucesso!';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="accordion" id="kbAccordion">
    <?php foreach ($categorias as $categoria): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-<?= $categoria['id_categoria'] ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#collapse-<?= $categoria['id_categoria'] ?>">
                    <strong><?= htmlspecialchars($categoria['nome_categoria']) ?></strong>
                </button>
            </h2>
            <div id="collapse-<?= $categoria['id_categoria'] ?>" class="accordion-collapse collapse" 
                 data-bs-parent="#kbAccordion">
                <div class="accordion-body">
                    <ul class="list-group">
                        <?php foreach ($artigos as $artigo): ?>
                            <?php if ($artigo['id_categoria_fk'] == $categoria['id_categoria']): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="ver.php?id=<?= $artigo['id_artigo'] ?>"><?= htmlspecialchars($artigo['titulo']) ?></a>
                                    <div>
                                        <a href="editar.php?id=<?= $artigo['id_artigo'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>