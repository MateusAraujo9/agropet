<?php
require "DAO/conection.php";

$idProduto = isset($_GET['produto'])?$_GET['produto']:"";

if (!empty($idProduto)){

    //Pegando o produto
    $sql = "SELECT * FROM produto WHERE id = '$idProduto'";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sql->rowCount()>0){
        $produto = $sql->fetch();
    }

    //Pegar unidade
    $sqlUnidades = "SELECT * FROM unidade";

    try{
        $sqlUnidades = $pdo->query($sqlUnidades);

        $sqlUnidades = $sqlUnidades->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar grupo
    $sqlGrupo = "SELECT * FROM grupo";

    try{
        $sqlGrupo = $pdo->query($sqlGrupo);

        $sqlGrupo = $sqlGrupo->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar Classe
    $sqlClasse = "SELECT * FROM classe";

    try{
        $sqlClasse = $pdo->query($sqlClasse);

        $sqlClasse = $sqlClasse->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar Subclasse
    $sqlSubClasse = "SELECT * FROM subclasse";

    try{
        $sqlSubClasse = $pdo->query($sqlSubClasse);

        $sqlSubClasse = $sqlSubClasse->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar fornecedor
    $sqlFornecedor = "SELECT nome FROM fornecedor WHERE id = '".$produto['id_fornecedor']."'";

    try{
        $sqlFornecedor = $pdo->query($sqlFornecedor);

        $sqlFornecedor = $sqlFornecedor->fetch();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
}

/*
 * Receber dados alterados via post
 */

$idProdutoPost = isset($_POST['idProduto'])?$_POST['idProduto']:"";
$nomeP = isset($_POST['nomeProduto'])?$_POST['nomeProduto']:"";
$barraP = isset($_POST['barra'])?$_POST['barra']:"";
$qtdP = isset($_POST['qtd'])?$_POST['qtd']:"";
$unidadeP = isset($_POST['unidade'])?$_POST['unidade']:"";
$grupoP = isset($_POST['grupo'])?$_POST['grupo']:"";
$classeP = isset($_POST['classe'])?$_POST['classe']:"";
$subclasseP = isset($_POST['subCl'])?$_POST['subCl']:"";
$fornecedorP = isset($_POST['fornecedor'])?$_POST['fornecedor']:"";
$vlComP = isset($_POST['vlComp'])?$_POST['vlComp']:"";
$vlVenP = isset($_POST['vlVen'])?$_POST['vlVen']:"";

//tratamento de valores
$qtdP = str_replace('.', '', $qtdP);
$qtdP = str_replace(',', '.', $qtdP);
$vlComP = str_replace('.', '', $vlComP);
$vlComP = str_replace(',', '.', $vlComP);
$vlVenP = str_replace('.', '', $vlVenP);
$vlVenP = str_replace(',', '.', $vlVenP);

//Busca fornecedor
$sqlF = "SELECT id FROM fornecedor WHERE nome = '$fornecedorP'";

try{
    $sqlF = $pdo->query($sqlF);

    $fornecedorP = $sqlF->fetch()['id'];
}catch (PDOException $e){
    echo "Erro".$e->getMessage();
}
if (!empty($idProdutoPost)){
    $sqlU = "UPDATE produto SET
             nome = '$nomeP',
             cod_barra = '$barraP',
             quantidade = '$qtdP',
             id_unidade = '$unidadeP',
             id_fornecedor = '$fornecedorP',
             id_grupo = '$grupoP',
             id_classe = '$classeP',
             id_subclasse = '$subclasseP',
             valor_compra = '$vlComP',
             valor_venda = '$vlVenP'
             WHERE id = '$idProdutoPost'";

    //echo "<br><br><hr><hr>".$sqlU."<br><br><hr>";

    try{
        $pdo->query($sqlU);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    header("Location: /#!/listProduto/");
}


?>
<head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="/resourse/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/resourse/css/style.css">
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
</head>
<h3 class="titulo">Editar Produto</h3>

<div class="container">
    <div class="containerCenter2">
        <!--Alert inicio -->
        <div id="compReact"></div>
        <!--Alert fim-->
        <a class="btn btn-outline-primary btn-sm" href="/#!/listProduto/"><img src="resourse/imagens/voltarP.png" class="imgVoltarP" alt="voltar">Voltar</a>
        <form method="post">
            <div class="form-group row">
                <fieldset class="col-md-2">
                    <label class="col-form-label" for="idProduto">Id</label>
                    <input class="form-control" id="idProduto" name="idProduto" type="text" value="<?php echo $produto['id']?>" readonly="">
                </fieldset>
                <fieldset class="col-md-10">
                    <label class="col-form-label" for="nomeProduto">Nome</label>
                    <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" value="<?php echo $produto['nome']?>">
                </fieldset>
            </div>
            <div class="form-group row">
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="barra">Cod Barra</label>
                    <input type="text" class="form-control" id="barra" name="barra" value="<?php echo $produto['cod_barra']?>">
                </fieldset>
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="qtd">Quantidade</label>
                    <input type="text" class="form-control quantidadeDec" id="qtd" name="qtd" maxlength="11" value="<?php echo $produto['quantidade']?>">
                </fieldset>
                <div class="col-md-2">
                    <label class="col-form-label" for="unidade">Unidade</label>
                    <select class="form-control" id="unidade" name="unidade">
                        <?php
                        foreach ($sqlUnidades as $un){
                            if ($un['id'] == $produto['id_unidade']){
                                echo "<option value=\"".$un['id']."\" selected>".$un['nome']." - ".$un['sigla']."</option>";
                            }else{
                                echo "<option value=\"".$un['id']."\">".$un['nome']." - ".$un['sigla']."</option>";
                            }

                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label" for="grupo">Grupo</label>
                    <select class="form-control" id="grupo" name="grupo">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlGrupo as $gr){
                            if ($gr['id'] == $produto['id_grupo']){
                                echo "<option value=\"".$gr['id']."\" selected>".$gr['nome']."</option>";
                            }else{
                                echo "<option value=\"".$gr['id']."\">".$gr['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label" for="classe">Classe</label>
                    <select class="form-control" id="classe" name="classe">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlClasse as $cl){
                            if ($cl['id']==$produto['id_classe']){
                                echo "<option value=\"".$cl['id']."\" selected>".$cl['nome']."</option>";
                            }else{
                                echo "<option value=\"".$cl['id']."\">".$cl['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label" for="subCl">Subclasse</label>
                    <select class="form-control" id="subCl" name="subCl">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlSubClasse as $sb){
                            if ($sb['id']==$produto['id_subclasse']){
                                echo "<option value=\"".$sb['id']."\" selected>".$sb['nome']."</option>";
                            }else{
                                echo "<option value=\"".$sb['id']."\">".$sb['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="col-form-label" for="fornecedor">Fornecedor</label>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="fornecedor" name="fornecedor" onblur="pesquisaFornecedor()" value="<?php echo $sqlFornecedor['nome']?>">
                            <div class="input-group-append">
                                <span class="input-group-text button" onclick="pesquisaFornecedor()"><img src="resourse/imagens/lupa.png" alt="lupa" title="Pesquisar"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label" for="vlComp">Valor de Compra</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control valor" id="vlComp" name="vlComp" maxlength="9" value="<?php echo $produto['valor_compra']?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label" for="vlVen">Valor de Venda</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control valor" id="vlVen" name="vlVen" maxlength="9" value="<?php echo $produto['valor_venda']?>">
                    </div>

                </div>
            </div>
            <input type="submit" value="Salvar" class="btn btn-primary btn-lg btn-block btnCadastro">
        </form>



    </div>
</div>