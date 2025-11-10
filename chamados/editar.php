<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Verificar o ID do chamado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID do chamado inválido.</div>";
    require_once '../includes/footer.php';
    exit;
}
$id_chamado = $_GET['id'];

// 3. Buscar os dados completos do chamado (com JOINs)
$sql = "SELECT 
            c.*, 
            a.nome_ativo, 
            u.nome_unidade, 
            cat.nome_categoria 
        FROM chamados c
        JOIN ativos a ON c.id_ativo_fk = a.id_ativo
        JOIN unidades u ON a.id_unidade_fk = u.id_unidade
        JOIN categorias cat ON c.id_categoria_fk = cat.id_categoria
        WHERE c.id_chamado = ?";
$stmt_chamado = $pdo->prepare($sql);
$stmt_chamado->execute([$id_chamado]);
$chamado = $stmt_chamado->fetch();

if (!$chamado) {
    echo "<div class='alert alert-danger'>Chamado não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}
?>

<h2>Resolver Chamado: #<?= $chamado['id_chamado'] ?></h2>
<hr>

<fieldset disabled>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Unidade</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($chamado['nome_unidade']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Ativo</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($chamado['nome_ativo']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Solicitante</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($chamado['solicitante']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Categoria</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($chamado['nome_categoria']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Aberto em</label>
            <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Problema Relatado</label>
            <textarea class="form-control" rows="3"><?= htmlspecialchars($chamado['problema_relatado']) ?></textarea>
        </div>
    </div>
</fieldset>

<hr>

<form action="processar.php" method="POST">
    <input type="hidden" name="acao" value="editar">
    <input type="hidden" name="id_chamado" value="<?= $chamado['id_chamado'] ?>">

    <div class="row g-3">
        <div class="col-md-6">
            <label for="status_chamado" class="form-label">Mudar Status</label>
            <select id="status_chamado" name="status_chamado" class="form-select" required>
                <option value="Aberto" <?= ($chamado['status_chamado'] == 'Aberto') ? 'selected' : '' ?>>Aberto</option>
                <option value="Em Andamento" <?= ($chamado['status_chamado'] == 'Em Andamento') ? 'selected' : '' ?>>Em Andamento</option>
                <option value="Aguardando" <?= ($chamado['status_chamado'] == 'Aguardando') ? 'selected' : '' ?>>Aguardando (Peça, Terceiros)</option>
                <option value="Fechado" <?= ($chamado['status_chamado'] == 'Fechado') ? 'selected' : '' ?>>Fechado (Resolvido)</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="prioridade" class="form-label">Mudar Prioridade</label>
            <select id="prioridade" name="prioridade" class="form-select" required>
                <option value="Baixa" <?= ($chamado['prioridade'] == 'Baixa') ? 'selected' : '' ?>>Baixa</option>
                <option value="Média" <?= ($chamado['prioridade'] == 'Média') ? 'selected' : '' ?>>Média</option>
                <option value="Alta" <?= ($chamado['prioridade'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                <option value="Crítica" <?= ($chamado['prioridade'] == 'Crítica') ? 'selected' : '' ?>>Crítica</option>
            </select>
        </div>

        <div class="col-12">
            <label for="solucao_aplicada" class="form-label">Solução Aplicada (O que você fez)</label>
            <textarea class="form-control" id="solucao_aplicada" name="solucao_aplicada" rows="6" 
                      placeholder="Descreva o que foi feito para resolver o problema..."><?= htmlspecialchars($chamado['solucao_aplicada']) ?></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">Atualizar Chamado</button>
            <a href="index.php" class="btn btn-secondary">Voltar para Lista</a>
        </div>
    </div>
</form>

<?php
// 4. Inclui o footer
require_once '../includes/footer.php';
?>