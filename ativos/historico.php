<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Verificar se o ID do ativo foi passado pela URL (GET)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID do ativo inválido.</div>";
    require_once '../includes/footer.php';
    exit; // Para a execução
}
$id_ativo = $_GET['id'];

// 3. Buscar os dados do Ativo (com JOIN na unidade)
$sql_ativo = "SELECT a.*, u.nome_unidade 
              FROM ativos a
              JOIN unidades u ON a.id_unidade_fk = u.id_unidade
              WHERE a.id_ativo = ?";
$stmt_ativo = $pdo->prepare($sql_ativo);
$stmt_ativo->execute([$id_ativo]);
$ativo = $stmt_ativo->fetch();

if (!$ativo) {
    echo "<div class='alert alert-danger'>Ativo não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}

// 4. Buscar o HISTÓRICO de chamados para este ativo (com JOIN na categoria)
$sql_chamados = "SELECT c.*, cat.nome_categoria
                 FROM chamados c
                 LEFT JOIN categorias cat ON c.id_categoria_fk = cat.id_categoria
                 WHERE c.id_ativo_fk = ?
                 ORDER BY c.data_abertura DESC"; // Mais recentes primeiro
$stmt_chamados = $pdo->prepare($sql_chamados);
$stmt_chamados->execute([$id_ativo]);
$chamados = $stmt_chamados->fetchAll();
?>

<h2>Histórico do Ativo</h2>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="mb-0"><?= htmlspecialchars($ativo['nome_ativo']) ?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Unidade:</strong> <?= htmlspecialchars($ativo['nome_unidade']) ?></div>
            <div class="col-md-4"><strong>IP:</strong> <?= htmlspecialchars($ativo['ip_address']) ?></div>
            <div class="col-md-4"><strong>ID Remoto:</strong> <?= htmlspecialchars($ativo['remote_id']) ?></div>
            <div class="col-md-4"><strong>Sistema:</strong> <?= htmlspecialchars($ativo['operating_system']) ?></div>
            <div class="col-md-4"><strong>Status:</strong> <?= htmlspecialchars($ativo['status_ativo']) ?></div>
            <div class="col-md-12 mt-2"><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($ativo['descricao'])) ?></div>
        </div>
    </div>
</div>

<h3>Histórico de Ocorrências (<?= count($chamados) ?>)</h3>
<a href="../chamados/novo.php?id_ativo=<?= $id_ativo ?>" class="btn btn-primary mb-3">Abrir Novo Chamado para este Ativo</a>

<div class="accordion" id="historicoChamados">
    <?php foreach ($chamados as $index => $chamado): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-<?= $index ?>">
                <button class="accordion-button <?= ($index > 0) ? 'collapsed' : '' ?>" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#collapse-<?= $index ?>">
                    <span class="badge bg-dark me-2">#<?= $chamado['id_chamado'] ?></span>
                    <span class="me-3"><?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?></span>
                    <strong class="me-3"><?= htmlspecialchars($chamado['status_chamado']) ?></strong>
                    <span><?= htmlspecialchars($chamado['problema_relatado']) ?></span>
                </button>
            </h2>
            <div id="collapse-<?= $index ?>" class="accordion-collapse collapse <?= ($index == 0) ? 'show' : '' ?>" 
                 data-bs-parent="#historicoChamados">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Problema Relatado:</strong>
                            <p><?= nl2br(htmlspecialchars($chamado['problema_relatado'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Solução Aplicada:</strong>
                            <p class="text-success"><?= nl2br(htmlspecialchars($chamado['solucao_aplicada'] ?? 'Nenhuma solução registrada.')) ?></p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Solicitante:</strong> <?= htmlspecialchars($chamado['solicitante']) ?></p>
                    <p><strong>Categoria:</strong> <?= htmlspecialchars($chamado['nome_categoria'] ?? 'N/A') ?></p>
                    <p><strong>Prioridade:</strong> <?= htmlspecialchars($chamado['prioridade']) ?></p>
                    <?php if ($chamado['data_fechamento']): ?>
                        <p><strong>Fechado em:</strong> <?= date('d/m/Y H:i', strtotime($chamado['data_fechamento'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (count($chamados) === 0): ?>
        <div class="alert alert-info">Nenhum chamado registrado para este ativo.</div>
    <?php endif; ?>
</div>

<a href="index.php" class="btn btn-secondary mt-4">Voltar para Lista de Ativos</a>

<?php
// 5. Inclui o footer
require_once '../includes/footer.php';
?>