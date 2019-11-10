<?php
require "DAO/conection.php";
$logado = isset($_COOKIE['logado'])?$_COOKIE['logado']:"";
$tkuser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"";

if ($logado == 'false' || empty($logado)){
    header("Location: /login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Agropet - Casa do Criador</title>

    <!--Bootstrap-->
    <link rel="stylesheet" href="resourse/css/bootstrap.css">


    <!--Meus links-->
    <link rel="stylesheet" href="resourse/css/style.css">
    <meta charset="utf-8">
</head>
<body ng-app="meuApp" onload="setPagina(2)">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#/!">Agropet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active" onmouseenter="subMenu('dropCadastro')" onmouseleave="subMenu('dropCadastro')">
                <a class="nav-link" href="#">Cadastro <span class="sr-only">(current)</span></a>
                <ul id="dropCadastro" class="esconde">
                    <a href="#!listProduto" class="linkMenu"><li class="itemDrop">Produto</li></a>
                    <a href="#!listGrupo" class="linkMenu"><li class="itemDrop">Grupo</li></a>
                    <a href="#!listClasse" class="linkMenu"><li class="itemDrop">Classe</li></a>
                    <a href="#!listSubclasse" class="linkMenu"><li class="itemDrop">Subclasse</li></a>
                    <a href="/#!/listCliente" class="linkMenu"><li class="itemDrop">Cliente</li></a>
                    <a href="#!listFornecedor" class="linkMenu"><li class="itemDrop">Fornecedor</li></a>
                    <a href="#!listUsuario" class="linkMenu"><li class="itemDrop">Usuário</li></a>
                </ul>
            </li>
            <li class="nav-item" onmouseenter="subMenu('dropGestaoProduto')" onmouseleave="subMenu('dropGestaoProduto')">
                <a class="nav-link" href="#">Gestão de Produto</a>
                <ul id="dropGestaoProduto" class="esconde">
                    <a href="#!entradaEstoque" class="linkMenu"><li class="itemDrop">Entrada de Estoque</li></a>
                    <a href="#!ajustePreco" class="linkMenu"><li class="itemDrop">Ajuste de Preço</li></a>
                    <a href="#!convertEstoque" class="linkMenu"><li class="itemDrop">Conversão de Estoque</li></a>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="caixa.php">Caixa</a>
            </li>
            <li class="nav-item" onmouseenter="subMenu('dropRelatorio')" onmouseleave="subMenu('dropRelatorio')">
                <a class="nav-link" href="#">Relatórios</a>
                <ul id="dropRelatorio" class="esconde">
                    <a href="/#!rVendas" class="linkMenu"><li class="itemDrop">Vendas</li></a>
                    <a href="/#!rCrediario" class="linkMenu"><li class="itemDrop">Crediário</li></a>
                </ul>
            </li>
        </ul>
        <?php
        if (!empty($tkuser)){
            $sql = "SELECT * FROM usuario WHERE token = '".$tkuser."'";
            $sql = $pdo->query($sql);

            $usuario = $sql->fetch();

            echo "<p class='userLogado'>Bem vindo(a), ".$usuario['nome']."</p><a class='btnSair' href='logout.php'>sair</a>";
        }


        ?>
    </div>
</nav>
<div ng-view ng-controller="meuAppCtrl">

</div>

<!--Bootstrap-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

<!--React-->
<script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
<script crossorigin src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

<!--Angular-->
<script src="resourse/js/angular.js"></script>
<script src="resourse/js/angular-route.js"></script>

<!--Meus links-->
<script src="resourse/js/script.js"></script>
<script type="text/babel" src="resourse/js/react.js"></script>

</body>
</html>
  