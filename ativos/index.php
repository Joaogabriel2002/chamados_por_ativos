<?php
// 1. Inclui o header e a conexão
require_once '../includes/header.php';
require_once '../config/conexao.php';

// --- INÍCIO DA LÓGICA DE FILTRO ---

// 2. Pegar os valores do filtro (se existirem)
// ?? '' significa que se $_GET['unidade'] não existir, $filtro_unidade será ''
$filtro_unidade = $_GET['unidade'] ?? '';
$filtro_status = $_GET['status'] ?? '';
$filtro_busca = $_GET['busca'] ?? '';

// 3. Buscar as unidades para o dropdown do filtro
$stmt_unidades = $pdo->query("SELECT id_unidade, nome_unidade FROM unidades ORDER BY nome_unidade");
$unidades_filtro = $stmt_unidades->fetchAll();

// 4. Montar a query SQL dinâmica
$sql = "SELECT a.*, u.nome_unidade 
        FROM ativos a
        LEFT JOIN unidades u ON a.id_unidade_fk = u.id_unidade";

$where_conditions = []; // Array para guardar as condições WHERE
$params = [];           // Array para guardar os parâmetros (para o prepared statement)

if (!empty($filtro_unidade)) {
    $where_conditions[] = "a.id_unidade_fk = ?";
    $params[] = $filtro_unidade;
}

if (!empty($filtro_status)) {
    $where_conditions[] = "a.status_ativo = ?";
    $params[] = $filtro_status;
}

if (!empty($filtro_busca)) {
    // Busca no nome do ativo, no IP ou no ID Remoto
    $where_conditions[] = "(a.nome_ativo LIKE ? OR a.ip_address LIKE ? OR a.remote_id LIKE ?)";
    $params[] = "%" . $filtro_busca . "%";
    $params[] = "%" . $filtro_busca . "%";
    $params[] = "%" . $filtro_busca . "%";
}

// Se houver condições, adiciona o WHERE na query
if (count($where_conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where_conditions);
}

$sql .= " ORDER BY u.nome_unidade, a.nome_ativo";

// 5. Executar a query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ativos = $stmt->fetchAll();

// --- FIM DA LÓGICA DE FILTRO ---
?>

<h2>Gestão de Ativos (Inventário)</h2>

<a href="novo.php" class="btn btn-primary mb-3">Adicionar Novo Ativo</a>

<div class="card bg-light mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="unidade" class="form-label">Filtrar por Unidade</label>
                <select name="unidade" id="unidade" class="form-select">
                    <option value="">-- Todas --</option>
                    <?php foreach ($unidades_filtro as $unidade): ?>
                        <option value="<?= $unidade['id_unidade'] ?>" 
                                <?= ($unidade['id_unidade'] == $filtro_unidade) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($unidade['nome_unidade']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Filtrar por Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="Ativo" <?= ('Ativo' == $filtro_status) ? 'selected' : '' ?>>Ativo</option>
                    <option value="Manutenção" <?= ('Manutenção' == $filtro_status) ? 'selected' : '' ?>>Manutenção</option>
                    <option value="Desativado" <?= ('Desativado' == $filtro_status) ? 'selected' : '' ?>>Desativado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="busca" class="form-label">Buscar (Nome, IP, ID)</label>
                <input type="text" name="busca" id="busca" class="form-control" 
                       value="<?= htmlspecialchars($filtro_busca) ?>" placeholder="Digite aqui...">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Filtrar</button>
                <a href="index.php" class="btn btn-secondary ms-2">Limpar</a>
            </div>
        </form>
    </div>
</div>
<?php if (isset($_GET['status']) && !isset($_GET['filtro_status'])): // Ajuste para não confundir com o filtro ?>
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
                    <a href="historico.php?id=<?= $ativo['id_ativo'] ?>" class="btn btn-info btn-sm">Histórico</a>
                    <a href="editar.php?id=<?= $ativo['id_ativo'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="processar.php?acao=excluir&id=<?= $ativo['id_ativo'] ?>" class="btn btn-danger btn-sm" 
                       onclick="return confirm('Tem certeza?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (count($ativos) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum ativo encontrado com esses filtros.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// 3. Inclui o footer
require_once '../includes/footer.php';
?>