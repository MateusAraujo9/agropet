<?php
require "conection.php";

$idCliente = isset($_POST['idCliente'])?$_POST['idCliente']:"";
$idCrediario = isset($_POST['idCrediario'])?$_POST['idCrediario']:"";
$especie = isset($_GET['especie'])?$_GET['especie']:"";

$sql = "UPDATE crediario SET valor_pago = valor_a_pagar, data_pagamento = NOW() WHERE id = $idCrediario AND id_cliente = $idCliente";

try{
    $return = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$return = $return == false?false:true;
if ($return){
    //Pegar id de caixa aberto
    $sqlCaixa = "SELECT id FROM caixa WHERE data_fechamento is null AND tipo = 'A'";
    try{
        $idCaixa = $pdo->query($sqlCaixa)->fetch()['id'];
    }catch (PDOException $e){
        echo "Erro na conexão: ".$e->getMessage();
    }

    //pega id da especie
    switch ($especie){
        case 'dinheiro':
            $idEspecie = 1;
            break;
        case 'debito':
            $idEspecie = 2;
            break;
        case 'credito':
            $idEspecie = 4;
            break;
        default:
            $idEspecie = 1;
    }

    //inserir na movimentação de caixa
    $sqlInsertMov = "INSERT INTO movimentacao_caixa (id_crediario, id_caixa, tipo, id_especie) 
                     VALUES ($idCrediario, $idCaixa, 'T', $idEspecie)";


    try{
        $pdo->query($sqlInsertMov);
    }catch (PDOException $e){
        echo "Erro de conexão: ".$e->getMessage();
    }

    //Pegar valor do crediario para debitar do valor comprado
    $sqlValorCrediario = "SELECT valor_a_pagar FROM crediario WHERE id = $idCrediario";
    try{
        $valor_a_pagar = $pdo->query($sqlValorCrediario)->fetch()['valor_a_pagar'];
    }catch (PDOException $e){
        $e->getMessage();
    }

    //update valor comprado
    $sqlValorComprado = "UPDATE cliente SET valor_comprado = valor_comprado - $valor_a_pagar WHERE id = $idCliente";

    try{
        $pdo->query($sqlValorComprado);
    }catch (PDOException $e){
        $e->getMessage();
    }
}