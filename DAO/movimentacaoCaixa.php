<?php
require "conection.php";

$tipo = isset($_POST['tipo'])?$_POST['tipo']:"";
$valor = isset($_POST['valor'])?$_POST['valor']:"";
$descricao = isset($_POST['descr'])?$_POST['descr']:"";

//formatação do valor
$novoValor = str_replace(",", ".", str_replace(".", "", $valor));

if ($tipo == "R" || $tipo == "D") {
    $cxAberto = "SELECT id FROM caixa WHERE data_fechamento is null";

    try{
        $cxAberto = $pdo->query($cxAberto);
    }catch (PDOException $e){
        $e->getMessage();
    }
    $cxAberto = $cxAberto->fetch();
    $idCaixa = $cxAberto['id'];
    //vai fazer movimentação
    $sql = "INSERT INTO movimentacao_caixa (id_caixa, tipo, id_especie, descricao, valor) VALUES ($idCaixa, '$tipo', 1, '$descricao', $novoValor)";

    try{
        $sql = $pdo->exec($sql);

    }catch (PDOException $e){
        $e->getMessage();
    }

    if ((boolean) $sql){
        echo "1";
    }else{
        echo "0";
    }
}
