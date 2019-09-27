<?php
require "conection.php";

$idP = isset($_POST['idProduto'])?$_POST['idProduto']:"";
$vlComp = isset($_POST['vlComp'])?$_POST['vlComp']:"";
$vlVen = isset($_POST['vlVen'])?$_POST['vlVen']:"";

//valor compra
$vlComp = str_replace(",", ".",$vlComp);

//valor venda
$vlVen = str_replace(",", ".", $vlVen);


$sql = "UPDATE produto SET valor_compra = '$vlComp', valor_venda = '$vlVen' WHERE id = '$idP'";

try{
    $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

header("Location: /#!/ajustePreco");