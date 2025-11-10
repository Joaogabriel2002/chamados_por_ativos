<?php
// 1. Inclui o header e a conexão (subindo um nível)
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Query SQL para buscar os ativos, JUNTANDO com a tabela unidades
// Usamos 'a' como apelido para 'ativos' e 'u' para 'unidades'
$sql = "SELECT a.*, u.nome_unidade 
        FROM ativos a
        LEFT JOIN unidades u ON a.id_unidade_fk = u.id_unidade
        ORDER BY u.nome_unidade, a.nome_ativo";

$stmt = $pdo->query($sql);
$ativos = $stmt->fetchAll();
?>

<h2>Gestão de Ativos (Inventário)</h2>

<a href="novo.php" class="btn btn-primary mb-3">Adicionar Novo Ativo</a>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['status'] == 'sucesso') echo 'Ativo cadastrado com sucesso!';
        if ($_GET['status'] == 'editado') echo 'Ativo atualizado com sucesso!';
        if ($_GET['status'] == 'excluido') echo 'Ativo excluído com sucesso!';
        if ($_GET['status'] == 'erro_fk_chamados') echo 'Erro: Este ativo não pode ser excluído, pois possui chamados vinculados.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome do Ativo</th>
                <th>Unidade</th>
                <th>IP</th>
                <th>ID Remoto</th>
                <th>Sistema</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ativos as $ativo): ?>
            <tr>
                <td><?= htmlspecialchars($ativo['nome_ativo']) ?></td>
                <td><?= htmlspecialchars($ativo['nome_unidade'] ?? 'Sem Unidade') ?></td>
                <td><?= htmlspecialchars($ativo['ip_address']) ?></td>
                <td><?= htmlspecialchars($ativo['remote_id']) ?></td>
                <td><?= htmlspecialchars($ativo['operating_system']) ?></td>
                <td>
                    <span class="badge 
                        <?php 
                        if ($ativo['status_ativo'] == 'Ativo') echo 'bg-success';
                        elseif ($ativo['status_ativo'] == 'Manutenção') echo 'bg-warning text-dark';
                        else echo 'bg-danger';
                        ?>">
                        <?= htmlspecialchars($ativo['status_ativo']) ?>
                    </span>
                </td>
                <td>
                    <a href="historico.php?id=<?= $ativo['id_ativo'] ?>" 
                       class="btn btn-info btn-sm">
                       Histórico
                    </a>
                    
                    <a href="editar.php?id=<?= $ativo['id_ativo'] ?>" 
                       class="btn btn-warning btn-sm">
                       Editar
                    </a>
                    
                    <a href="processar.php?acao=excluir&id=<?= $ativo['id_ativo'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Tem certeza?');">
                       Excluir
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (count($ativos) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum ativo cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>