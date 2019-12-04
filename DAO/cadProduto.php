<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";
$barra = isset($_POST['barra'])?$_POST['barra']:"";
$quantidade = isset($_POST['qtd']) &&$_POST['qtd'] != ""?$_POST['qtd']:0;
$unidade = isset($_POST['unidade']) && $_POST['unidade'] != "0"?$_POST['unidade']:1;
$fornecedor = isset($_POST['fornecedor']) && $_POST['fornecedor'] != ""?$_POST['fornecedor']:1;
$grupo = isset($_POST['grupo']) && $_POST['grupo'] != "0"?$_POST['grupo']:1;
$classe = isset($_POST['classe']) && $_POST['classe'] != "0"?$_POST['classe']:1;
$subclasse = isset($_POST['subCl']) && $_POST['subCl'] != "0"?$_POST['subCl']:1;
$vlCompra = isset($_POST['vlComp']) && $_POST['vlComp'] != ""?$_POST['vlComp']:0;
$vlVenda = isset($_POST['vlVen']) && $_POST['vlVen'] != ""?$_POST['vlVen']:0;

if (empty($nome)){
    echo "erro nome";
}else if( empty($vlVenda)){
    echo "erro vlVenda";
}else{
    //trocar , por .
    $vlVenda = str_replace(',', '.', $vlVenda);
    $vlCompra = str_replace(',', '.', $vlCompra);

    //Pegar id fornecedor
    if ($fornecedor != 1){
        $sqlF = "SELECT id FROM fornecedor WHERE nome = '$fornecedor'";

        try{
            $sqlF = $pdo->query($sqlF);

            $idFornecedor = $sqlF->fetch()['id'];
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }else{
        $idFornecedor = $fornecedor;
    }


    $sql = "INSERT INTO produto (nome, cod_barra, quantidade, id_unidade, id_fornecedor, id_grupo, id_classe, id_subclasse, data_inclusao, valor_compra, valor_venda) 
            VALUES ('$nome', '$barra', '$quantidade', '$unidade', '$idFornecedor', '$grupo', '$classe', '$subclasse', now(), '$vlCompra', '$vlVenda')";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //antes !$sql
//    if($sql == false) {
//        echo "erro produto";
//    }
//    header("Location: /#!/listProduto");
}







