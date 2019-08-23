<!DOCTYPE html>
<html>
<head>
    <title>Agropet - Casa do Criador</title>

    <!--Bootstrap-->
    <link rel="stylesheet" href="resourse/css/bootstrap.css">

    <link rel="stylesheet" href="resourse/css/style.css">
    <script src="resourse/js/script.js"></script>
    <meta charset="utf-8">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Agropet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active" onmouseenter="subMenu('dropCadastro')" onmouseleave="subMenu('dropCadastro')">
                <a class="nav-link" href="#">Cadastro <span class="sr-only">(current)</span></a>
                <ul id="dropCadastro" class="esconde">
                    <li class="itemDrop">Produto</li>
                    <li class="itemDrop">Cliente</li>
                    <li class="itemDrop">Fornecedor</li>
                    <li class="itemDrop">Usuário</li>
                    <li class="itemDrop">Grupo</li>
                    <li class="itemDrop">Classe</li>
                    <li class="itemDrop">Subclasse</li>
                </ul>
            </li>
            <li class="nav-item" onmouseenter="subMenu('dropGestaoProduto')" onmouseleave="subMenu('dropGestaoProduto')">
                <a class="nav-link" href="#">Gestão de Produto</a>
                <ul id="dropGestaoProduto" class="esconde">
                    <li class="itemDrop">Entrada de Estoque</li>
                    <li class="itemDrop">Ajuste de Preço</li>
                    <li class="itemDrop">Conversão de Estoque</li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Caixa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Relatórios</a>
            </li>
        </ul>
    </div>
</nav>
</body>
</html>
  