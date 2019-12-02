<?php
require "conection.php";

$idVenda = isset($_GET['idVenda'])?$_GET['idVenda']:"";

$sql = "SELECT I.id as id_item_venda, P.id, P.nome, I.quantidade, I.valor_total FROM itens_venda I, produto P 
          WHERE I.id_venda = $idVenda
          AND I.id_produto = P.id";

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    $e->getMessage();
}

if($sql->rowCount() > 0){
  $sql = $sql->fetchAll();
  print_r(json_encode($sql));
}



