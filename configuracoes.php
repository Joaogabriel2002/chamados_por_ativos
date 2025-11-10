<?php
// 1. Inclui o header
// (O header.php já tem o $base_url, então podemos usá-lo)
require_once 'includes/header.php';
?>

<h2>Configurações do Sistema</h2>
<p>Gerencie os parâmetros básicos do seu sistema de Help Desk.</p>
<hr>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">Gerenciar Unidades</h5>
                <p class="card-text">Cadastre ou remova os locais (supermercados, postos) que recebem suporte.</p>
                <a href="<?= $base_url ?>/unidades/" class="btn btn-primary mt-auto">Acessar Unidades</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">Gerenciar Categorias</h5>
                <p class="card-text">Defina os tipos de problemas (Hardware, Software, Rede) para classificar os chamados.</p>
                <a href="<?= $base_url ?>/categorias/" class="btn btn-primary mt-auto">Acessar Categorias</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">Base de Conhecimento (KB)</h5>
                <p class="card-text">Consulte e crie artigos com soluções padrão para problemas comuns.</p>
                <a href="<?= $base_url ?>/kb/" class="btn btn-primary mt-auto">Acessar KB</a>
            </div>
        </div>
    </div>

</div>

<?php
// 2. Inclui o footer
require_once 'includes/footer.php';
?>