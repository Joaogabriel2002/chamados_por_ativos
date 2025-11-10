<?php
// 1. Inclui a conexão
require_once '../config/conexao.php';

// 2. LÓGICA DE ADICIONAR (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    
    $nome_categoria = $_POST['nome_categoria'];

    if (empty($nome_categoria)) {
        die("O nome da categoria é obrigatório.");
    }

    $sql = "INSERT INTO categorias (nome_categoria) VALUES (?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nome_categoria]);
        header("Location: index.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao cadastrar categoria: " . $e->getMessage());
    }
}


// 3. LÓGICA DE EXCLUIR (via GET)
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {

    $id_categoria = $_GET['id'];
    $sql = "DELETE FROM categorias WHERE id_categoria = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$id_categoria]);
        header("Location: index.php?status=excluido");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            // Erro de chave estrangeira
            header("Location: index.php?status=erro_fk");
        } else {
            die("Erro ao excluir categoria: " . $e->getMessage());
        }
    }
}

// Se nenhuma ação válida, redireciona
header("Location: index.php");
exit;
?>