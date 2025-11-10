<?php
// Defina o caminho base do seu projeto.
$base_url = '/sistema_chamados'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Chamados TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $base_url ?>/index.php">Painel TI</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?= $base_url ?>/chamados/">Chamados</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $base_url ?>/ativos/">Ativos</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="<?= $base_url ?>/configuracoes.php">Configurações</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">