<?php
// 1. Inclui o header (subindo um nível)
require_once '../includes/header.php';

// 2. Inclui a conexão (subindo um nível)
require_once '../config/conexao.php';

// 3. Buscar todas as unidades no banco
$stmt = $pdo->query("SELECT * FROM unidades ORDER BY nome_unidade");
$unidades = $stmt->fetchAll();
?>

<h2>Gestão de Unidades</h2>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['status'] == 'sucesso') echo 'Unidade cadastrada com sucesso!';
        if ($_GET['status'] == 'excluido') echo 'Unidade excluída com sucesso!';
        if ($_GET['status'] == 'erro_fk') echo 'Erro: Esta unidade não pode ser excluída, pois possui ativos vinculados.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <h3>Cadastrar Nova Unidade</h3>
        
        <form action="processar.php" method="POST" class="bg-light p-3 rounded shadow-sm">
            <div class="mb-3">
                <label for="nome_unidade" class="form-label">Nome da Unidade</label>
                <input type="text" class="form-control" id="nome_unidade" name="nome_unidade" required>
            </div>
            <div class="mb-3">
                <label for="tipo_unidade" class="form-label">Tipo (Ex: Supermercado)</label>
                <input type="text" class="form-control" id="tipo_unidade" name="tipo_unidade">
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco">
            </div>
            
            <input type="hidden" name="acao" value="adicionar">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>

    <div class="col-md-8">
        <h3>Unidades Cadastradas</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unidades as $unidade): ?>
                <tr>
                    <td><?= htmlspecialchars($unidade['nome_unidade']) ?></td>
                    <td><?= htmlspecialchars($unidade['tipo_unidade']) ?></td>
                    <td><?= htmlspecialchars($unidade['endereco']) ?></td>
                    <td>
                        <a href="processar.php?acao=excluir&id=<?= $unidade['id_unidade'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Tem certeza?');">
                           Excluir
                        </a>
                        </td>
                </tr>
                <?php endforeach; ?>

                <?php if (count($unidades) === 0): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma unidade cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// 4. Inclui o footer (subindo um nível)
require_once '../includes/footer.php';
?>