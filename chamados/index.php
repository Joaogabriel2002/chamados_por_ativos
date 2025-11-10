<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Definir o filtro de status (qual aba está ativa)
$status_filtro = $_GET['status'] ?? 'Abertos'; // Padrão é 'Abertos'

// 3. Montar a query SQL com base no filtro
$sql = "SELECT 
            c.id_chamado,
            c.problema_relatado,
            c.status_chamado,
            c.prioridade,
            c.data_abertura,
            a.nome_ativo,
            u.nome_unidade,
            cat.nome_categoria
        FROM chamados c
        JOIN ativos a ON c.id_ativo_fk = a.id_ativo
        JOIN unidades u ON a.id_unidade_fk = u.id_unidade
        JOIN categorias cat ON c.id_categoria_fk = cat.id_categoria
        ";

// Adicionar a cláusula WHERE de acordo com a aba
if ($status_filtro == 'Abertos') {
    $sql .= " WHERE c.status_chamado = 'Aberto'";
} elseif ($status_filtro == 'Em Andamento') {
    $sql .= " WHERE c.status_chamado = 'Em Andamento'";
} elseif ($status_filtro == 'Fechados') {
    $sql .= " WHERE c.status_chamado = 'Fechado'";
}
// Se for 'Todos', não adiciona WHERE

$sql .= " ORDER BY c.data_abertura DESC";

$stmt = $pdo->query($sql);
$chamados = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gestão de Chamados</h2>
    <a href="novo.php" class="btn btn-primary">Abrir Novo Chamado</a>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?= ($status_filtro == 'Abertos') ? 'active' : '' ?>" href="?status=Abertos">Abertos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($status_filtro == 'Em Andamento') ? 'active' : '' ?>" href="?status=Em Andamento">Em Andamento</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($status_filtro == 'Fechados') ? 'active' : '' ?>" href="?status=Fechados">Fechados</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($status_filtro == 'Todos') ? 'active' : '' ?>" href="?status=Todos">Todos</a>
    </li>
</ul>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['msg'] == 'sucesso') echo 'Chamado aberto com sucesso!';
        if ($_GET['msg'] == 'editado') echo 'Chamado atualizado com sucesso!';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Ativo</th>
                <th>Unidade</th>
                <th>Categoria</th>
                <th>Problema</th>
                <th>Aberto em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chamados as $chamado): ?>
            <tr>
                <td>
                    <span class="badge 
                        <?php 
                        if ($chamado['prioridade'] == 'Crítica') echo 'bg-danger';
                        elseif ($chamado['prioridade'] == 'Alta') echo 'bg-warning text-dark';
                        elseif ($chamado['prioridade'] == 'Média') echo 'bg-info';
                        else echo 'bg-secondary';
                        ?>">
                        <?= htmlspecialchars($chamado['prioridade']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($chamado['status_chamado']) ?></td>
                <td><?= htmlspecialchars($chamado['nome_ativo']) ?></td>
                <td><?= htmlspecialchars($chamado['nome_unidade']) ?></td>
                <td><?= htmlspecialchars($chamado['nome_categoria']) ?></td>
                <td><?= htmlspecialchars(substr($chamado['problema_relatado'], 0, 50)) . '...' ?></td>
                <td><?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?></td>
                <td>
                    <a href="editar.php?id=<?= $chamado['id_chamado'] ?>" class="btn btn-warning btn-sm">
                        Ver / Resolver
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (count($chamados) === 0): ?>
                <tr>
                    <td colspan="8" class="text-center">Nenhum chamado encontrado para este filtro.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>