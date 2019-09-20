<?php
require "conection.php";

$listaProdutos = isset($_POST['lista'])?$_POST['lista']:"";
$tipoVenda = isset($_POST['tipo'])?$_POST['tipo']:"";
$subtotal = isset($_POST['subtotal'])?$_POST['subtotal']:"";
$cliente = isset($_POST['cliente'])?$_POST['cliente']:"";
$especie = isset($_POST['especie'])?$_POST['especie']:"";


if ($listaProdutos === "" || $tipoVenda === "" || $subtotal === "" || $especie === ""){
    echo "erro";
}elseif ($especie === "crediario" && $cliente === ""){
    echo "erro";
}else{
    //Pegar usuario e caixa
    $tkUser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"0";

    $sql = "SELECT C.id as id_caixa, U.id as id_user, U.nome as nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkUser' AND C.tipo = 'A'";

    try{
        $sql = $pdo->query($sql);

        $result = $sql->fetch();
    }catch (PDOException $e){
        $e->getMessage();
    }

    //Pegar id cliente
    if ($cliente !== ""){
        $sqlCli = "SELECT * FROM cliente WHERE nome = '$cliente'";

        try{
            $sqlCli = $pdo->query($sqlCli);
            $dadosCliente = $sqlCli->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }
    }
    //definir id especie
    switch ($especie){
        case "dinheiro":
            $id_especie = 1;
            break;
        case "cartao":
            $id_especie = 2;
            break;
        case "crediario":
            $id_especie = 3;
            break;
    }

    //Insert Venda
    if ($sqlCli->rowCount()>0){
        $sqlInsertVenda = "INSERT INTO venda (id_usuario, id_cliente, valor_total, id_especie, tipo_venda, data_venda, id_caixa)
                        VALUE ('".$result['id_user']."', '".$dadosCliente['id']."', '$subtotal', '$id_especie', '$tipoVenda', now(), '".$result['id_caixa']."')";
    }else{
        $sqlInsertVenda = "INSERT INTO venda (id_usuario, valor_total, id_especie, tipo_venda, data_venda, id_caixa)
                        VALUE ('".$result['id_user']."', '$subtotal', '$id_especie', '$tipoVenda', now(), '".$result['id_caixa']."')";
    }

    try{
        $pdo->query($sqlInsertVenda);
    }catch (PDOException $e){
        $e->getMessage();
    }
    //Pegar id da venda
    $sqlPegaVenda = "SELECT max(id) as numero_venda FROM venda WHERE id_usuario = '".$result['id_user']."'";
    try{
        $sqlPegaVenda = $pdo->query($sqlPegaVenda);
        $sqlPegaVenda = $sqlPegaVenda->fetch();
    }catch (PDOException $e){
        $e->getMessage();
    }
    //Insert Itens
    foreach ($listaProdutos as $produto){
        $sqlInsertItem = "INSERT INTO itens_venda (id_venda, id_produto, quantidade, valor_total, desconto, valor_bruto, valor_liquido_unitario) 
                          VALUES ('".$sqlPegaVenda['numero_venda']."', '".$produto['id']."', '".$produto['quantidade']."', '".$produto['vlTotal']."', '".$produto['desconto']."',
                          '".$produto['vlBruto']."', '".$produto['vlLiquido']."')";

        try{
            $pdo->query($sqlInsertItem);
        }catch (PDOException $e){
            $e->getMessage();
        }
    }
    //Caso crediario deve inserir crediario
    if ($id_especie === 3){
        $vencimento = date('Y-m-d', strtotime('+30 days'));
        $sqlInsertCrediario = "INSERT INTO crediario (id_cliente, valor_a_pagar, data_inclusao, data_vencimento, numero_venda) 
                                VALUES ('".$dadosCliente['id']."', '$subtotal', now(), '$vencimento', '".$sqlPegaVenda['numero_venda']."')";

        try{
            $pdo->query($sqlInsertCrediario);
        }catch (PDOException $e){
            $e->getMessage();
        }
    }
}