<?php
require "DAO/conection.php";
$logado = isset($_COOKIE['logado'])?$_COOKIE['logado']:"";
$tkuser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"";

if ($logado == 'false' || empty($logado)){
    header("Location: /login.php");
}

$sqlCaixa = "SELECT C.id, U.nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkuser' AND C.tipo = 'A'";

try{
    $sqlCaixa = $pdo->query($sqlCaixa);

    $result = $sqlCaixa->fetch();
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
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
    <link rel="stylesheet" href="resourse/css/style.css">
    <script src="resourse/js/script.js"></script>
    <script type="text/babel" src="resourse/js/react.js"></script>
    <meta charset="utf-8">
</head>
<body ng-app="meuApp" onload="setPagina(2)">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/#/!">Agropet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active" onmouseenter="subMenu('dropCadastro')" onmouseleave="subMenu('dropCadastro')">
                <a class="nav-link" href="/#">Cadastro <span class="sr-only">(current)</span></a>
                <ul id="dropCadastro" class="esconde">
                    <a href="/#!listProduto" class="linkMenu"><li class="itemDrop">Produto</li></a>
                    <a href="/#!listGrupo" class="linkMenu"><li class="itemDrop">Grupo</li></a>
                    <a href="/#!listClasse" class="linkMenu"><li class="itemDrop">Classe</li></a>
                    <a href="/#!listSubclasse" class="linkMenu"><li class="itemDrop">Subclasse</li></a>
                    <a href="/#!/listCliente" class="linkMenu"><li class="itemDrop">Cliente</li></a>
                    <a href="/#!listFornecedor" class="linkMenu"><li class="itemDrop">Fornecedor</li></a>
                    <a href="/#!listUsuario" class="linkMenu"><li class="itemDrop">Usuário</li></a>
                </ul>
            </li>
            <li class="nav-item" onmouseenter="subMenu('dropGestaoProduto')" onmouseleave="subMenu('dropGestaoProduto')">
                <a class="nav-link" href="/#">Gestão de Produto</a>
                <ul id="dropGestaoProduto" class="esconde">
                    <a href="/#!entradaEstoque" class="linkMenu"><li class="itemDrop">Entrada de Estoque</li></a>
                    <a href="/#!ajustePreco" class="linkMenu"><li class="itemDrop">Ajuste de Preço</li></a>
                    <a href="/#!convertEstoque" class="linkMenu"><li class="itemDrop">Conversão de Estoque</li></a>
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
<div>
    <script>
        $(window).load(function() {
            $('#produto').focus();

            if (listaPCaixa.length > 0) {
                alert("Venda em andamento, gostaria de cancelar?");
            }
        });

        $(document).keypress(function(e) {
            if (e.which === 13) {
                if (e.target.getAttribute("id") === "produto") {
                    let caixa;
                    $.when(consultaCaixaAberto()).done(caixa = statusCaixa);
                    if (caixa){
                        let pesquisa = $('#produto')[0].value;
                        pesquisaProdutoCaixa(pesquisa);
                    } else{
                        alert("Caixa Fechado");
                        console.log(caixa);
                    }
                }else if(e.target.getAttribute("id") === "quantidade" || e.target.getAttribute("id") === "vlUnit"){
                    let quant = $('#quantidade')[0].value.replace(",", ".");
                    if (quant !== "" && quant != null && quant > 0){
                        produtoC.quantidade = quant;
                        produtoC.vlTotal = $('#vlUnit')[0].value.replace(",", ".");
                        produtoC.vlTotal = parseFloat(produtoC.vlTotal).toFixed(2);
                        $('#desconto').focus();
                    }
                }else if(e.target.getAttribute("id") === "desconto"){
                    let caixa;
                    $.when(consultaCaixaAberto()).done(caixa = statusCaixa);
                    let p =  $('#produto');
                    if (caixa) {
                        if (p[0].value !== "" && p[0].value !== null) {
                            let elDesconto = $('#desconto')[0];
                            let elVlVen = $('#vlUnit')[0];

                            if (elDesconto.value === "") {
                                elDesconto.value = 0;
                            }
                            //Salvando valor do desconto
                            let desconto = parseFloat(elDesconto.value.replace(",", "."));
                            produtoC.desconto = desconto;

                            let novoValor = produtoC.vlBruto - (produtoC.vlBruto * (desconto / 100));

                            produtoC.vlLiquido = parseFloat(novoValor.toFixed(2));
                            produtoC.vlTotal = parseFloat((produtoC.vlLiquido * produtoC.quantidade).toFixed(2));

                            elVlVen.value = parseFloat(produtoC.vlTotal);

                            listaPCaixa.push(produtoC);
                            subtotalCaixa = parseFloat((subtotalCaixa + produtoC.vlTotal).toFixed(2));
                            produtoC = {};
                            $('#subtotal')[0].value = subtotalCaixa;
                            p.focus();

                            atualizarListaCaixa(listaPCaixa);
                            apagarCamposCaixa();
                        }else{
                            alert("Informe o Produto!");
                        }
                    }else{
                        alert("Caixa Fechado");
                    }
                }
            }
        });
    </script>
    <div class="container telaCaixa">
        <div id="compReact"></div>
        <div class="titleCaixa">
            <h3 class="titulo">CAIXA</h3>
            <div class="form-group opcoesCaixa">
                <select class="custom-select" onchange="acoesCaixa(this)" id="selectCaixa">
                    <option selected=""></option>
                    <option value="1">Abrir Caixa</option>
                    <option value="2">Fechar Caixa</option>
                    <option value="3">Cancelar Venda</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <input class="form-control form-control-lg" type="text" placeholder="Id/CEAN/Nome Produto" id="produto">
        </div>
        <div class="corpoCaixa">
            <div class="componentR" id="componentR"></div>
            <div class="valoresCaixa">
                <div class="linhaCaixa">
                    <div class="form-group camposCaixa">
                        <label class="col-form-label col-form-label-lg" for="quantidade">Quantidade</label>
                        <input class="form-control form-control-lg inputCaixaQtd numeroCaixa" type="text" id="quantidade" name="quantidade" maxlength="12" onkeyup="calcularValor(this)">
                    </div>
                    <div class="form-group camposCaixa">
                        <label class="col-form-label col-form-label-lg" for="vlUnit">Valor Venda</label>
                        <input class="form-control form-control-lg numeroCaixa" type="text" id="vlUnit" name="vlUnit" onkeyup="calcularQuantidade(this)" maxlength="12">
                    </div>
                </div>
                <div class="linhaCaixa">
                    <div class="form-group camposCaixa">
                        <label class="col-form-label col-form-label-lg" for="desconto">Desconto (%)</label>
                        <input class="form-control form-control-lg desconto" type="text" id="desconto" name="desconto">
                    </div>
                    <div class="form-group camposCaixa">
                        <label class="col-form-label col-form-label-lg" for="subtotal">Subtotal</label>
                        <input class="form-control form-control-lg inputCaixaQtd" type="text" id="subtotal" name="subtotal" readonly="">
                    </div>
                </div>
                <div class="linhaCaixa">
                    <button class="btn btn-success btn-block botaoCaixa" onclick="abrirModalFinalizarVenda(listaPCaixa, 'V', subtotalCaixa)">FINALIZAR VENDA</button>
                </div>
            </div>
        </div>
        <div class="footer" id="footer">
            <?php
            if ($sqlCaixa->rowCount() > 0){
                //print_r($sqlCaixa->rowCount());
                echo "<p id='pFooterCaixa'>CAIXA ABERTO - Nº Caixa: ".$result['id']." - Usuario: ".$result['nome']."</p>";
            }
            ?>
        </div>

    </div>
</div>
</body>
</html>

