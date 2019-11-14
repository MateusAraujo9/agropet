<?php
require "conection.php";

$idCrediario = isset($_GET['idCrediario'])?$_GET['idCrediario']:"";

//Buscar numero da venda
$sqlNumVenda = "SELECT numero_venda FROM crediario WHERE id = $idCrediario";

try{
    $idVenda = $pdo->query($sqlNumVenda)->fetch()['numero_venda'];
}catch (PDOException $e){
    $e->getMessage();
}

if ($idVenda == null){
    echo "parcial";
}else {
//Buscar lista de itens

    $listItens = "SELECT
              I.id as id_item_venda, 
              P.id as id_produto,
              P.nome,
              I.quantidade,
              I.valor_total,
              I.id_venda
              FROM  itens_venda I, produto P
              WHERE I.id_produto = P.id
              AND I.id_venda = $idVenda";


    try{
        $listItens = $pdo->query($listItens);
    }catch(PDOException $e){
        $e->getMessage();
    }

    if ($listItens->rowCount() >0){
        $return = $listItens->fetchAll();
        print_r(json_encode($return));
    }

}