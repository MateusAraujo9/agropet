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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <!--React-->
    <script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>

    <!--Angular-->
    <script src="resourse/js/angular.js"></script>
    <script src="resourse/js/angular-route.js"></script>

    <!--Meus links-->
    <link rel="stylesheet" href="resourse/css/style.css">
    <script src="resourse/js/script.js"></script>
    <meta charset="utf-8">
</head>
<body ng-app="meuApp">
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
                    <li class="itemDrop">Produto</li>
                    <li class="itemDrop">Grupo</li>
                    <li class="itemDrop">Classe</li>
                    <li class="itemDrop">Subclasse</li>
                    <li class="itemDrop">Cliente</li>
                    <li class="itemDrop">Fornecedor</li>
                    <a href="#!listUsuario" class="linkMenu"><li class="itemDrop">Usuário</li></a>
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
        <?php
        if (!empty($tkuser)){
            $sql = "SELECT * FROM usuario WHERE token = '".$tkuser."'";
            $sql = $pdo->query($sql);

            $usuario = $sql->fetch();

            echo "<p class='userLogado'>Bem vindo, ".$usuario['nome']."</p><a class='btnSair' href='logout.php'>sair</a>";
        }


        ?>
    </div>
</nav>
<div ng-view></div>
</body>
</html>
  