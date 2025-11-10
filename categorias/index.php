<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Buscar todas as categorias no banco
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome_categoria");
$categorias = $stmt->fetchAll();
?>

<h2>Gestão de Categorias de Chamados</h2>
<p>Use as categorias para classificar os problemas (Ex: Hardware, Software, Rede, Impressora).</p>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['status'] == 'sucesso') echo 'Categoria cadastrada com sucesso!';
        if ($_GET['status'] == 'excluido') echo 'Categoria excluída com sucesso!';
        if ($_GET['status'] == 'erro_fk') echo 'Erro: Esta categoria não pode ser excluída, pois está em uso por chamados ou artigos da K.B.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <h3>Cadastrar Nova Categoria</h3>
        
        <form action="processar.php" method="POST" class="bg-light p-3 rounded shadow-sm">
            <div class="mb-3">
                <label for="nome_categoria" class="form-label">Nome da Categoria</label>
                <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" required>
            </div>
            
            <input type="hidden" name="acao" value="adicionar">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>

    <div class="col-md-8">
        <h3>Categorias Cadastradas</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= htmlspecialchars($categoria['nome_categoria']) ?></td>
                    <td>
                        <a href="processar.php?acao=excluir&id=<?= $categoria['id_categoria'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Tem certeza?');">
                           Excluir
                        </a>
                        </td>
                </tr>
                <?php endforeach; ?>

                <?php if (count($categorias) === 0): ?>
                    <tr>
                        <td colspan="2" class="text-center">Nenhuma categoria cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>