<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";
$barra = isset($_POST['barra'])?$_POST['barra']:"";
$quantidade = isset($_POST['qtd'])?$_POST['qtd']:"";
$unidade = isset($_POST['unidade'])?$_POST['unidade']:"";
$fornecedor = isset($_POST['fornecedor'])?$_POST['fornecedor']:"";
$grupo = isset($_POST['grupo'])?$_POST['grupo']:"";
$classe = isset($_POST['classe'])?$_POST['classe']:"";
$subclasse = isset($_POST['subCl'])?$_POST['subCl']:"";
$vlCompra = isset($_POST['vlComp'])?$_POST['vlComp']:"";
$vlVenda = isset($_POST['vlVen'])?$_POST['vlVen']:"";

if (
    empty($nome)||
    empty($barra)||
    empty($quantidade)||
    empty($unidade)||
    empty($fornecedor)||
    empty($grupo)||
    empty($classe)||
    empty($subclasse)||
    empty($vlVenda)
){
    echo "<script>";
    echo "alert('Passado algum campo vazio.');";
    echo "window.location.href = \"/#!/listProduto\"";
    echo "</script>";
}else{
    //Pegar id fornecedor
    $sqlF = "SELECT id FROM fornecedor WHERE nome = '$fornecedor'";

    try{
        $sqlF = $pdo->query($sqlF);

        $idFornecedor = $sqlF->fetch()['id'];
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }


    $sql = "INSERT INTO produto (nome, cod_barra, quantidade, id_unidade, id_fornecedor, id_grupo, id_classe, id_subclasse, data_inclusao, valor_compra, valor_venda) 
            VALUES ('$nome', '$barra', '$quantidade', '$unidade', '$idFornecedor', '$grupo', '$classe', '$subclasse', now(), '$vlCompra', '$vlVenda')";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if(!$sql) {
        echo "<script>";
        echo "alert('Produto Não cadastrado');";
        echo "window.location.href = \"/#!/listProduto\"";
        echo "</script>";

    }
    header("Location: /#!/listProduto");
}







