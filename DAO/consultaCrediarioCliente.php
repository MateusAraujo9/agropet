<?php
require "conection.php";

$id_cliente = isset($_GET['idCliente'])?$_GET['idCliente']:"";
$id_crediario = isset($_GET['idCrediario'])?$_GET['idCrediario']:"";

$sql = "SELECT 
        L.id as id_cliente,
        C.id as id_crediario,
        L.nome as nome, 
        C.valor_a_pagar as valor_a_pagar
        FROM crediario C, cliente L
        WHERE C.id_cliente = L.id
        AND L.id = $id_cliente
        AND C.id = $id_crediario";


try{
    $retorno = $pdo->query($sql);
}catch (PDOException $e){
    $e->getMessage();
}


if ($retorno->rowCount() > 0){
    print_r(json_encode($retorno->fetch()));
}