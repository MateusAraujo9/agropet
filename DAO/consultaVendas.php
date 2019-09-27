<?php
require "conection.php";

$dtIni = isset($_POST['dtIni'])?$_POST['dtIni']:"";
$dtFim = isset($_POST['dtFim'])?$_POST['dtFim']:"";
$soCliente = isset($_POST['soCliente'])?$_POST['soCliente']:"";
$porItem = isset($_POST['porItem'])?$_POST['porItem']:"";
$cliente = isset($_POST['cliente'])?$_POST['cliente']:"";


if ($dtIni != "" && $dtFim != ""){
    if ($cliente != ""){
        $sqlCli = "SELECT * FROM  cliente WHERE nome = '$cliente'";

        try{
            $sqlCli = $pdo->query($sqlCli);
            $sqlCli = $sqlCli->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        if ($porItem === "false"){
            $sqlVendas = "SELECT * FROM venda WHERE id_cliente = '".$sqlCli['id']."' AND data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }else{
            $sqlVendas = "SELECT 
                          V.data_venda as dtVenda,
                          V.id as nVenda, 
                          P.nome as produto,
                          I.quantidade as quantidade,
                          I.valor_liquido_unitario as vlLiquido,
                          I.valor_bruto as vlBruto,
                          I.desconto as desconto,
                          I.valor_total as vlTotal
                          FROM venda V, itens_venda I, produto P 
                          WHERE V.id = I.id_venda
                          AND I.id_produto = P.id 
                          AND V.id_cliente = '".$sqlCli['id']."' 
                          AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }

    }else if ($soCliente === "true"){
        if ($porItem === "false"){
            $sqlVendas = "SELECT * FROM venda WHERE id_cliente is not null AND data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }else{
            $sqlVendas = "SELECT * FROM venda V, itens_venda I WHERE V.id = I.id_venda AND V.id_cliente is not null AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }
    }else{
        if ($porItem === "false"){
            $sqlVendas = "SELECT * FROM venda WHERE data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }else{
            $sqlVendas = "SELECT * FROM venda V, itens_venda I WHERE V.id = I.id_venda AND data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";
        }
    }
}

try{
    $retorno = $pdo->query($sqlVendas);
}catch (PDOException $e){
    $e->getMessage();
}

if ($retorno->rowCount() > 0){
    $retorno = $retorno->fetchAll();
}

echo json_encode($retorno);


//echo $sqlVendas;
