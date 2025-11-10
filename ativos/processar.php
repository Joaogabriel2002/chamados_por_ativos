<?php
// 1. Inclui a conexão
require_once '../config/conexao.php';

// --- LÓGICA DE ADICIONAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    // ... (código de coleta de dados) ...
    $id_unidade_fk = $_POST['id_unidade_fk'];
    $nome_ativo = $_POST['nome_ativo'];
    $descricao = $_POST['descricao'] ?? null;
    $ip_address = $_POST['ip_address'] ?? null;
    $remote_id = $_POST['remote_id'] ?? null;
    $operating_system = $_POST['operating_system'] ?? null;
    $tipo_ativo = $_POST['tipo_ativo'] ?? null; 

    // ... (validação) ...
    // SQL
    $sql = "INSERT INTO ativos (id_unidade_fk, nome_ativo, descricao, ip_address, remote_id, operating_system, tipo_ativo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"; 
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_unidade_fk, $nome_ativo, $descricao, $ip_address, $remote_id, $operating_system, $tipo_ativo]);
        
        // --- CORREÇÃO AQUI ---
        header("Location: index.php?msg=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao cadastrar ativo: " . $e->getMessage());
    }
}


// --- LÓGICA DE EDITAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    // ... (código de coleta de dados) ...
    $id_ativo = $_POST['id_ativo'];
    $id_unidade_fk = $_POST['id_unidade_fk'];
    $nome_ativo = $_POST['nome_ativo'];
    $descricao = $_POST['descricao'] ?? null;
    $ip_address = $_POST['ip_address'] ?? null;
    $remote_id = $_POST['remote_id'] ?? null;
    $operating_system = $_POST['operating_system'] ?? null;
    $status_ativo = $_POST['status_ativo'];
    $tipo_ativo = $_POST['tipo_ativo'] ?? null; 
    
    // ... (validação) ...
    // SQL
    $sql = "UPDATE ativos SET 
                id_unidade_fk = ?, nome_ativo = ?, descricao = ?, ip_address = ?, 
                remote_id = ?, operating_system = ?, tipo_ativo = ?, status_ativo = ?
            WHERE id_ativo = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id_unidade_fk, $nome_ativo, $descricao, $ip_address, 
            $remote_id, $operating_system, $tipo_ativo, $status_ativo, $id_ativo
        ]);
        
        // --- CORREÇÃO AQUI ---
        header("Location: index.php?msg=editado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao editar ativo: " . $e->getMessage()); 
    }
}


// --- LÓGICA DE EXCLUIR (GET) ---
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_ativo = $_GET['id'];
    $sql = "DELETE FROM ativos WHERE id_ativo = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_ativo]);
        
        // --- CORREÇÃO AQUI ---
        header("Location: index.php?msg=excluido");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            // --- CORREÇÃO AQUI ---
            header("Location: index.php?msg=erro_fk_chamados");
        } else {
            die("Erro ao excluir ativo: " . $e->getMessage());
        }
    }
}

header("Location: index.php");
exit;
?>