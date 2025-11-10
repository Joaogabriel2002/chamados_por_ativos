<?php
// 1. Inclui a conexão
require_once '../config/conexao.php';

// --- LÓGICA DE ADICIONAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {

    $titulo = $_POST['titulo'];
    $id_categoria_fk = $_POST['id_categoria_fk'];
    $conteudo = $_POST['conteudo'];

    if (empty($titulo) || empty($id_categoria_fk) || empty($conteudo)) {
        die("Todos os campos são obrigatórios.");
    }

    $sql = "INSERT INTO base_conhecimento (titulo, id_categoria_fk, conteudo, data_criacao) 
            VALUES (?, ?, ?, NOW())";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $id_categoria_fk, $conteudo]);
        header("Location: index.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar artigo: " . $e->getMessage());
    }
}


// --- LÓGICA DE EDITAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {

    $id_artigo = $_POST['id_artigo'];
    $titulo = $_POST['titulo'];
    $id_categoria_fk = $_POST['id_categoria_fk'];
    $conteudo = $_POST['conteudo'];

    if (empty($id_artigo) || empty($titulo) || empty($id_categoria_fk) || empty($conteudo)) {
        die("Todos os campos são obrigatórios.");
    }

    $sql = "UPDATE base_conhecimento SET 
                titulo = ?, 
                id_categoria_fk = ?, 
                conteudo = ?, 
                ultima_atualizacao = NOW()
            WHERE id_artigo = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $id_categoria_fk, $conteudo, $id_artigo]);
        header("Location: ver.php?id=$id_artigo&status=editado"); // Volta para a página do artigo
        exit;
    } catch (PDOException $e) {
        die("Erro ao editar artigo: " . $e->getMessage());
    }
}


// --- LÓGICA DE EXCLUIR (GET) ---
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {

    $id_artigo = $_GET['id'];
    $sql = "DELETE FROM base_conhecimento WHERE id_artigo = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_artigo]);
        header("Location: index.php?status=excluido");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir artigo: " . $e->getMessage());
    }
}

// Se nenhuma ação válida, redireciona
header("Location: index.php");
exit;
?>