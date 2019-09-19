<?php
require "conection.php";

$tkUser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"0";

$sqlVerifica = $sql = "SELECT C.id, U.nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkUser' AND C.tipo = 'A'";

try{
    $sqlVerifica = $pdo->query($sqlVerifica);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

if ($sqlVerifica->rowCount()===0){
    echo "false";
}else{
    echo "true";
}