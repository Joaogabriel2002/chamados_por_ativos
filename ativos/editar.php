<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Verificar o ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID do ativo inválido.</div>";
    require_once '../includes/footer.php';
    exit; 
}
$id_ativo = $_GET['id'];

// 3. Buscar os dados do ativo
$stmt_ativo = $pdo->prepare("SELECT * FROM ativos WHERE id_ativo = ?");
$stmt_ativo->execute([$id_ativo]);
$ativo = $stmt_ativo->fetch();

if (!$ativo) {
    echo "<div class='alert alert-danger'>Ativo não encontrado.</div>";
    require_once '../includes/footer.php';
    exit;
}

// 4. Buscar as unidades
$stmt_unidades = $pdo->query("SELECT * FROM unidades ORDER BY nome_unidade");
$unidades = $stmt_unidades->fetchAll();
?>

<h2>Editar Ativo: <?= htmlspecialchars($ativo['nome_ativo']) ?></h2>
<hr>

<form action="processar.php" method="POST" class="row g-3">
    <input type="hidden" name="acao" value="editar">
    <input type="hidden" name="id_ativo" value="<?= $ativo['id_ativo'] ?>">

    <div class="col-md-6">
        <label for="nome_ativo" class="form-label">Nome do Ativo</label>
        <input type="text" class="form-control" id="nome_ativo" name="nome_ativo" 
               value="<?= htmlspecialchars($ativo['nome_ativo']) ?>" required>
    </div>
    <div class="col-md-6">
        <label for="id_unidade_fk" class="form-label">Unidade (Local)</label>
        <select id="id_unidade_fk" name="id_unidade_fk" class="form-select" required>
            <option value="">-- Selecione a unidade --</option>
            <?php foreach ($unidades as $unidade): ?>
                <option value="<?= $unidade['id_unidade'] ?>" 
                    <?= ($unidade['id_unidade'] == $ativo['id_unidade_fk']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($unidade['nome_unidade']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="ip_address" class="form-label">Endereço IP</label>
        <input type="text" class="form-control" id="ip_address" name="ip_address" 
               value="<?= htmlspecialchars($ativo['ip_address']) ?>">
    </div>
    <div class="col-md-4">
        <label for="remote_id" class="form-label">ID Acesso Remoto (AnyDesk)</label>
        <input type="text" class="form-control" id="remote_id" name="remote_id" 
               value="<?= htmlspecialchars($ativo['remote_id']) ?>">
    </div>
    <div class="col-md-4">
        <label for="operating_system" class="form-label">Sistema Operacional</label>
        <input type="text" class="form-control" id="operating_system" name="operating_system" 
               value="<?= htmlspecialchars($ativo['operating_system']) ?>">
    </div>
    
    <div class="col-md-6">
        <label for="tipo_ativo" class="form-label">Tipo de Ativo</label>
        <select id="tipo_ativo" name="tipo_ativo" class="form-select">
            <option value="">-- Selecione o tipo --</option>
            <?php $tipo_selecionado = $ativo['tipo_ativo']; ?>
            <option value="PDV" <?= ($tipo_selecionado == 'PDV') ? 'selected' : '' ?>>PDV (Ponto de Venda)</option>
            <option value="Notebook" <?= ($tipo_selecionado == 'Notebook') ? 'selected' : '' ?>>Notebook</option>
            <option value="Desktop" <?= ($tipo_selecionado == 'Desktop') ? 'selected' : '' ?>>Desktop</option>
            <option value="Servidor" <?= ($tipo_selecionado == 'Servidor') ? 'selected' : '' ?>>Servidor</option>
            <option value="Impressora" <?= ($tipo_selecionado == 'Impressora') ? 'selected' : '' ?>>Impressora</option>
            <option value="Roteador" <?= ($tipo_selecionado == 'Roteador') ? 'selected' : '' ?>>Roteador</option>
            <option value="Outro" <?= ($tipo_selecionado == 'Outro') ? 'selected' : '' ?>>Outro</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="status_ativo" class="form-label">Status</label>
        <select id="status_ativo" name="status_ativo" class="form-select" required>
            <option value="Ativo" <?= ($ativo['status_ativo'] == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
            <option value="Manutenção" <?= ($ativo['status_ativo'] == 'Manutenção') ? 'selected' : '' ?>>Manutenção</option>
            <option value="Desativado" <?= ($ativo['status_ativo'] == 'Desativado') ? 'selected' : '' ?>>Desativado</option>
        </select>
    </div>

    <div class="col-md-12">
        <label for="descricao" class="form-label">Descrição / Observações</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($ativo['descricao']) ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php
// 5. Inclui o footer
require_once '../includes/footer.php';
?>