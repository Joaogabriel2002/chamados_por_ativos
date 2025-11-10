<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Buscar Categorias
$stmt_categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome_categoria");
$categorias = $stmt_categorias->fetchAll();
?>

<h2>Novo Artigo da Base de Conhecimento</h2>
<hr>

<form action="processar.php" method="POST" class="row g-3">
    <input type="hidden" name="acao" value="adicionar">

    <div class="col-md-8">
        <label for="titulo" class="form-label">Título do Artigo</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required>
    </div>

    <div class="col-md-4">
        <label for="id_categoria_fk" class="form-label">Categoria</label>
        <select id="id_categoria_fk" name="id_categoria_fk" class="form-select" required>
            <option value="" selected disabled>-- Selecione --</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria'] ?>">
                    <?= htmlspecialchars($categoria['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label for="conteudo" class="form-label">Conteúdo (Procedimento / Solução)</label>
        <textarea class="form-control" id="conteudo" name="conteudo" rows="15" 
                  placeholder="Descreva a solução passo a passo..."></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar Artigo</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>