<?php
// 1. Inclui a conexão
require_once '../config/conexao.php';

// --- LÓGICA DE ADICIONAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {

    // Coletar dados
    $id_unidade_fk = $_POST['id_unidade_fk'];
    $nome_ativo = $_POST['nome_ativo'];
    $descricao = $_POST['descricao'] ?? null;
    $ip_address = $_POST['ip_address'] ?? null;
    $remote_id = $_POST['remote_id'] ?? null;
    $operating_system = $_POST['operating_system'] ?? null;
    // status_ativo já tem 'Ativo' como padrão no banco

    // Validar
    if (empty($id_unidade_fk) || empty($nome_ativo)) {
        die("Nome do ativo e Unidade são obrigatórios.");
    }

    // SQL
    $sql = "INSERT INTO ativos (id_unidade_fk, nome_ativo, descricao, ip_address, remote_id, operating_system) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_unidade_fk, $nome_ativo, $descricao, $ip_address, $remote_id, $operating_system]);
        
        header("Location: index.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao cadastrar ativo: " . $e->getMessage());
    }
}


// --- LÓGICA DE EDITAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {

    // Coletar dados
    $id_ativo = $_POST['id_ativo'];
    $id_unidade_fk = $_POST['id_unidade_fk'];
    $nome_ativo = $_POST['nome_ativo'];
    $descricao = $_POST['descricao'] ?? null;
    $ip_address = $_POST['ip_address'] ?? null;
    $remote_id = $_POST['remote_id'] ?? null;
    $operating_system = $_POST['operating_system'] ?? null;
    $status_ativo = $_POST['status_ativo'];

    // Validar
    if (empty($id_ativo) || empty($id_unidade_fk) || empty($nome_ativo)) {
        die("ID, Nome do ativo e Unidade são obrigatórios.");
    }

    // SQL
    $sql = "UPDATE ativos SET 
                id_unidade_fk = ?, 
                nome_ativo = ?, 
                descricao = ?, 
                ip_address = ?, 
                remote_id = ?, 
                operating_system = ?, 
                status_ativo = ?
            WHERE id_ativo = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id_unidade_fk, 
            $nome_ativo, 
            $descricao, 
            $ip_address, 
            $remote_id, 
            $operating_system, 
            $status_ativo, 
            $id_ativo
        ]);
        
        header("Location: index.php?status=editado");
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
        
        header("Location: index.php?status=excluido");
        exit;

    } catch (PDOException $e) {
        // Erro de Chave Estrangeira (FK)
        if ($e->getCode() == '23000') {
            // Não pode excluir pois existem 'chamados' vinculados
            header("Location: index.php?status=erro_fk_chamados");
        } else {
            die("Erro ao excluir ativo: " . $e->getMessage());
        }
    }
}

// Se nenhuma ação válida for encontrada, redireciona
header("Location: index.php");
exit;
?>