<?php
require "conection.php";

$tipo = isset($_POST['tipo'])?$_POST['tipo']:"";
$id = isset($_POST['id'])?$_POST['id']:"";

if ($tipo == "produto"){
    $sql = "UPDATE produto SET data_exclusao = NOW() WHERE id = $id";
}elseif ($tipo == "cliente"){
    $sql = "UPDATE cliente SET data_exclusao = NOW() WHERE id = $id";
}elseif ($tipo == "fornecedor"){
    $sql = "UPDATE fornecedor SET data_exclusao = NOW() WHERE id = $id";
}

if ($sql != ""){
    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo $e->getMessage();
    }
}

if ($sql == false){
    echo "erro inesperado";
}else{
    echo "sucesso";
}