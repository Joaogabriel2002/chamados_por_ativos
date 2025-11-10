<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Artigo não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}
$id_artigo = $_GET['id'];

// 3. Buscar o artigo
$stmt_artigo = $pdo->prepare("SELECT * FROM base_conhecimento WHERE id_artigo = ?");
$stmt_artigo->execute([$id_artigo]);
$artigo = $stmt_artigo->fetch();

if (!$artigo) {
    echo "<div class='alert alert-danger'>Artigo não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}

// 4. Buscar Categorias
$stmt_categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome_categoria");
$categorias = $stmt_categorias->fetchAll();
?>

<h2>Editar Artigo da KB</h2>
<hr>

<form action="processar.php" method="POST" class="row g-3">
    <input type="hidden" name="acao" value="editar">
    <input type="hidden" name="id_artigo" value="<?= $artigo['id_artigo'] ?>">

    <div class="col-md-8">
        <label for="titulo" class="form-label">Título do Artigo</label>
        <input type="text" class="form-control" id="titulo" name="titulo" 
               value="<?= htmlspecialchars($artigo['titulo']) ?>" required>
    </div>

    <div class="col-md-4">
        <label for="id_categoria_fk" class="form-label">Categoria</label>
        <select id="id_categoria_fk" name="id_categoria_fk" class="form-select" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria'] ?>"
                    <?= ($categoria['id_categoria'] == $artigo['id_categoria_fk']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label for="conteudo" class="form-label">Conteúdo (Procedimento / Solução)</label>
        <textarea class="form-control" id="conteudo" name="conteudo" rows="15"><?= htmlspecialchars($artigo['conteudo']) ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="ver.php?id=<?= $artigo['id_artigo'] ?>" class="btn btn-secondary">Cancelar</a>
        <a href="processar.php?acao=excluir&id=<?= $artigo['id_artigo'] ?>" 
           class="btn btn-danger float-end" 
           onclick="return confirm('Tem certeza que deseja excluir este artigo?');">
           Excluir Artigo
        </a>
    </div>
</form>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>