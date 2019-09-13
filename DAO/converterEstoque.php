<?php
require "conection.php";

$idPOrigem = isset($_POST['idProdutoOrigem'])?$_POST['idProdutoOrigem']:"0";
$qtdPOrigem = isset($_POST['estoqueOrigem'])?$_POST['estoqueOrigem']:"0";

$idPDestino = isset($_POST['idProdutoDestino'])?$_POST['idProdutoDestino']:"0";
$qtdPDestino = isset($_POST['estoqueDestino'])?$_POST['estoqueDestino']:"0";

//echo "Destino ID: ".$idPDestino."<br> Dest QTD: ".$qtdPDestino."<br> Origem ID: ".$idPOrigem."<br> Origem QTD: ".$qtdPOrigem;

$sqlOrigem = "UPDATE produto SET quantidade = quantidade - $qtdPOrigem WHERE id = '$idPOrigem'";

$sqlDestino = "UPDATE produto SET quantidade = quantidade + $qtdPDestino WHERE id = '$idPDestino'";


try{
    $pdo->query($sqlOrigem);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}


try{
    $pdo->query($sqlDestino);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

header("Location: /#!/convertEstoque");