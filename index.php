<?php
// 1. Inclui o header e a conexão
require_once 'includes/header.php';
require_once 'config/conexao.php';

// --- INÍCIO DAS CONSULTAS PARA O DASHBOARD ---

// 2. Contagem de Chamados
$stmt_abertos = $pdo->query("SELECT COUNT(*) FROM chamados WHERE status_chamado = 'Aberto'");
$total_abertos = $stmt_abertos->fetchColumn();

$stmt_andamento = $pdo->query("SELECT COUNT(*) FROM chamados WHERE status_chamado = 'Em Andamento'");
$total_andamento = $stmt_andamento->fetchColumn();

// 3. Contagem de Ativos
$stmt_ativos = $pdo->query("SELECT COUNT(*) FROM ativos");
$total_ativos = $stmt_ativos->fetchColumn();

$stmt_manutencao = $pdo->query("SELECT COUNT(*) FROM ativos WHERE status_ativo = 'Manutenção'");
$total_manutencao = $stmt_manutencao->fetchColumn();

// 4. Buscar os 5 últimos chamados abertos ou em andamento (os mais urgentes)
$sql_recentes = "SELECT 
                    c.id_chamado,
                    c.problema_relatado,
                    c.prioridade,
                    a.nome_ativo,
                    u.nome_unidade
                 FROM chamados c
                 JOIN ativos a ON c.id_ativo_fk = a.id_ativo
                 JOIN unidades u ON a.id_unidade_fk = u.id_unidade
                 WHERE c.status_chamado = 'Aberto' OR c.status_chamado = 'Em Andamento'
                 ORDER BY c.data_abertura DESC
                 LIMIT 5";
$stmt_recentes = $pdo->query($sql_recentes);
$chamados_recentes = $stmt_recentes->fetchAll();

// --- FIM DAS CONSULTAS ---
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Chamados Abertos</h5>
                <p class="card-text display-4"><?= $total_abertos ?></p>
                <a href="<?= $base_url ?>/chamados/?status=Abertos" class="text-white stretched-link">Ver Lista</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-dark bg-warning shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Em Andamento</h5>
                <p class="card-text display-4"><?= $total_andamento ?></p>
                <a href="<?= $base_url ?>/chamados/?status=Em Andamento" class="text-dark stretched-link">Ver Lista</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Ativos em Manutenção</h5>
                <p class="card-text display-4"><?= $total_manutencao ?></p>
                <a href="<?= $base_url ?>/ativos/" class="text-white stretched-link">Ver Inventário</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total de Ativos</h5>
                <p class="card-text display-4"><?= $total_ativos ?></p>
                <a href="<?= $base_url ?>/ativos/" class="text-white stretched-link">Ver Inventário</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Ações Rápidas</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= $base_url ?>/chamados/novo.php" class="btn btn-primary btn-lg">Abrir Novo Chamado</a>
                    <a href="<?= $base_url ?>/ativos/novo.php" class="btn btn-info btn-lg">Cadastrar Ativo</a>
                    <a href="<?= $base_url ?>/unidades/" class="btn btn-secondary">Gerenciar Unidades</a>
                    <a href="<?= $base_url ?>/categorias/" class="btn btn-secondary">Gerenciar Categorias</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Últimos Chamados Urgentes</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <?php foreach ($chamados_recentes as $chamado): ?>
                            <tr>
                                <td>
                                    <a href="<?= $base_url ?>/chamados/editar.php?id=<?= $chamado['id_chamado'] ?>" class="text-decoration-none">
                                        <strong><?= htmlspecialchars($chamado['problema_relatado']) ?></strong>
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($chamado['nome_unidade']) ?> - <?= htmlspecialchars($chamado['nome_ativo']) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                        if ($chamado['prioridade'] == 'Crítica') echo 'bg-danger';
                                        elseif ($chamado['prioridade'] == 'Alta') echo 'bg-warning text-dark';
                                        else echo 'bg-info';
                                        ?>">
                                        <?= htmlspecialchars($chamado['prioridade']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (count($chamados_recentes) === 0): ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhum chamado aberto ou em andamento. ✨</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// 3. Inclui o footer
require_once 'includes/footer.php';
?>