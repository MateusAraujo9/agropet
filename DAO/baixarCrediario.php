<?php
require "conection.php";

$id = isset($_POST['id'])?$_POST['id']:"";
$valorPago = isset($_POST['valorPago'])?$_POST['valorPago']:"";

$sql = "UPDATE cliente SET valor_comprado = valor_comprado - $valorPago WHERE id = $id";

try{
    $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}