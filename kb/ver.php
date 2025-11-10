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
$sql = "SELECT b.*, c.nome_categoria 
        FROM base_conhecimento b
        LEFT JOIN categorias c ON b.id_categoria_fk = c.id_categoria
        WHERE b.id_artigo = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_artigo]);
$artigo = $stmt->fetch();

if (!$artigo) {
    echo "<div class='alert alert-danger'>Artigo não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}
?>

<h2><?= htmlspecialchars($artigo['titulo']) ?></h2>
<hr>

<div class="mb-3">
    <span class="badge bg-secondary"><?= htmlspecialchars($artigo['nome_categoria'] ?? 'Sem Categoria') ?></span>
</div>

<div class="card bg-light">
    <div class="card-body">
        <?= nl2br(htmlspecialchars($artigo['conteudo'])) ?>
    </div>
</div>

<a href="index.php" class="btn btn-primary mt-4">Voltar para a Base</a>
<a href="editar.php?id=<?= $artigo['id_artigo'] ?>" class="btn btn-warning mt-4">Editar Artigo</a>

<?php
// 4. Inclui o footer
require_once '../includes/footer.php';
?>