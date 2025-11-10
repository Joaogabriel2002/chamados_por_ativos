<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// 2. Buscar Categorias
$stmt_categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome_categoria");
$categorias = $stmt_categorias->fetchAll();

// 3. Buscar Ativos (junto com a Unidade, para facilitar a seleção)
$sql_ativos = "SELECT a.id_ativo, a.nome_ativo, u.nome_unidade 
               FROM ativos a
               JOIN unidades u ON a.id_unidade_fk = u.id_unidade
               WHERE a.status_ativo = 'Ativo'
               ORDER BY u.nome_unidade, a.nome_ativo";
$stmt_ativos = $pdo->query($sql_ativos);
$ativos = $stmt_ativos->fetchAll();
?>

<h2>Abrir Novo Chamado</h2>
<hr>

<form action="processar.php" method="POST" class="row g-3">
    <input type="hidden" name="acao" value="adicionar">

    <div class="col-md-8">
        <label for="id_ativo_fk" class="form-label">Ativo (Equipamento)</label>
        <select id="id_ativo_fk" name="id_ativo_fk" class="form-select" required>
            <option value="" selected disabled>-- Selecione o ativo (Unidade - Ativo) --</option>
            <?php foreach ($ativos as $ativo): ?>
                <option value="<?= $ativo['id_ativo'] ?>">
                    <?= htmlspecialchars($ativo['nome_unidade']) ?> - <?= htmlspecialchars($ativo['nome_ativo']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="solicitante" class="form-label">Solicitante</label>
        <input type="text" class="form-control" id="solicitante" name="solicitante" placeholder="Ex: Maria (Caixa)" required>
    </div>

    <div class="col-md-6">
        <label for="id_categoria_fk" class="form-label">Categoria do Problema</label>
        <select id="id_categoria_fk" name="id_categoria_fk" class="form-select" required>
            <option value="" selected disabled>-- Selecione a categoria --</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria'] ?>">
                    <?= htmlspecialchars($categoria['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select id="prioridade" name="prioridade" class="form-select" required>
            <option value="Baixa">Baixa</option>
            <option value="Média" selected>Média</option>
            <option value="Alta">Alta</option>
            <option value="Crítica">Crítica</option>
        </select>
    </div>

    <div class="col-12">
        <label for="problema_relatado" class="form-label">Problema Relatado</label>
        <textarea class="form-control" id="problema_relatado" name="problema_relatado" rows="5" required
                  placeholder="Descreva o problema relatado pelo solicitante..."></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Abrir Chamado</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php
// 4. Inclui o footer
require_once '../includes/footer.php';
?>