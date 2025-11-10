<?php
// Configurações do Banco de Dados
$db_host = 'localhost';
$db_name = 'helpdesk_db'; // Coloque o nome do seu banco de dados
$db_user = 'root';
$db_pass = ''; // Sua senha do MySQL (deixe em branco se for o padrão do XAMPP)

// Configurar o DSN (Data Source Name)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa 'prepared statements' reais
];

try {
    // Cria a instância do PDO
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Se a conexão falhar, exibe o erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>