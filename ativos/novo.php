<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Buscar as unidades para popular o <select>
$stmt_unidades = $pdo->query("SELECT * FROM unidades ORDER BY nome_unidade");
$unidades = $stmt_unidades->fetchAll();
?>

<h2>Cadastrar Novo Ativo</h2>
<hr>

<form action="processar.php" method="POST" class="row g-3">
    <input type="hidden" name="acao" value="adicionar">

    <div class="col-md-6">
        <label for="nome_ativo" class="form-label">Nome do Ativo (Ex: Caixa 01, PC-Financeiro)</label>
        <input type="text" class="form-control" id="nome_ativo" name="nome_ativo" required>
    </div>

    <div class="col-md-6">
        <label for="id_unidade_fk" class="form-label">Unidade (Local)</label>
        <select id="id_unidade_fk" name="id_unidade_fk" class="form-select" required>
            <option value="" selected disabled>-- Selecione a unidade --</option>
            <?php foreach ($unidades as $unidade): ?>
                <option value="<?= $unidade['id_unidade'] ?>">
                    <?= htmlspecialchars($unidade['nome_unidade']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="ip_address" class="form-label">Endereço IP</label>
        <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.10">
    </div>

    <div class="col-md-4">
        <label for="remote_id" class="form-label">ID Acesso Remoto (AnyDesk)</label>
        <input type="text" class="form-control" id="remote_id" name="remote_id" placeholder="123 456 789">
    </div>

    <div class="col-md-4">
        <label for="operating_system" class="form-label">Sistema Operacional</label>
        <input type="text" class="form-control" id="operating_system" name="operating_system" placeholder="Windows 10 Pro">
    </div>

    <div class="col-md-12">
        <label for="descricao" class="form-label">Descrição / Observações</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Ex: PC HP 8GB RAM, SSD 240GB..."></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar Ativo</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>