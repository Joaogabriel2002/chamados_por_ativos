<?php
// 1. Inclui a conexão
require_once '../config/conexao.php';

// --- LÓGICA DE ADICIONAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {

    // Coletar dados do formulário novo.php
    $id_ativo_fk = $_POST['id_ativo_fk'];
    $id_categoria_fk = $_POST['id_categoria_fk'];
    $solicitante = $_POST['solicitante'];
    $prioridade = $_POST['prioridade'];
    $problema_relatado = $_POST['problema_relatado'];
    
    // Status e data são definidos automaticamente
    // status_chamado (padrão 'Aberto' no banco)
    // data_abertura (padrão CURRENT_TIMESTAMP no banco)

    // Validar
    if (empty($id_ativo_fk) || empty($id_categoria_fk) || empty($solicitante) || empty($problema_relatado)) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    // SQL
    $sql = "INSERT INTO chamados (id_ativo_fk, id_categoria_fk, solicitante, problema_relatado, prioridade) 
            VALUES (?, ?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_ativo_fk, $id_categoria_fk, $solicitante, $problema_relatado, $prioridade]);
        
        header("Location: index.php?msg=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao abrir chamado: " . $e->getMessage());
    }
}


// --- LÓGICA DE EDITAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {

    // Coletar dados do formulário editar.php
    $id_chamado = $_POST['id_chamado'];
    $status_chamado = $_POST['status_chamado'];
    $prioridade = $_POST['prioridade'];
    $solucao_aplicada = $_POST['solucao_aplicada'] ?? null;
    
    // Validar
    if (empty($id_chamado) || empty($status_chamado) || empty($prioridade)) {
        die("ID, Status e Prioridade são obrigatórios.");
    }

    // SQL
    $sql = "UPDATE chamados SET 
                status_chamado = ?, 
                prioridade = ?, 
                solucao_aplicada = ?,
                data_fechamento = CASE 
                                     WHEN ? = 'Fechado' AND data_fechamento IS NULL THEN NOW()
                                     WHEN ? != 'Fechado' THEN NULL
                                     ELSE data_fechamento 
                                  END
            WHERE id_chamado = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $status_chamado,
            $prioridade,
            $solucao_aplicada,
            $status_chamado, // Usado no CASE
            $status_chamado, // Usado no CASE
            $id_chamado
        ]);
        
        header("Location: index.php?msg=editado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao atualizar chamado: " . $e->getMessage());
    }
}

// Se nenhuma ação válida, redireciona
header("Location: index.php");
exit;
?>