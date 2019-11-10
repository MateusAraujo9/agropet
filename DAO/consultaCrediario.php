<?php
require "conection.php";

$tipo = isset($_GET['tipo'])?$_GET['tipo']:"";
$dtIni = isset($_GET['dtIni'])?$_GET['dtIni']:"";
$dtFim = isset($_GET['dtFim'])?$_GET['dtFim']:"";
$cliente = isset($_GET['cliente'])?$_GET['cliente']:"";
$pagina = isset($_GET['pagina'])?$_GET['pagina']:"";

$itemInicial = $pagina*10-10;

//Pegar id do cliente
if (!empty($cliente)){
    $sqlCli = "SELECT id FROM cliente WHERE nome = '$cliente'";

    try{
        $idCli = $pdo->query($sqlCli)->fetch();
    }catch (PDOException $e){
        $e->getMessage();
    }
}

if ($cliente == ""){
    if ($tipo == "todos"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                CONCAT('R$ ',REPLACE(CAST(C.valor_pago as decimal(15,2)), '.', ',')) AS valor_pago,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento,
                DATE_FORMAT(C.data_pagamento, '%d/%m/%Y %H:%i:%S') AS data_pagamento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['valor_pago'] = "Valor Pago";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";
        $header['data_pagamento'] = "Dt Pagamento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'";

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;

    }elseif ($tipo == "pago"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                CONCAT('R$ ',REPLACE(CAST(C.valor_pago as decimal(15,2)), '.', ',')) AS valor_pago,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento,
                DATE_FORMAT(C.data_pagamento, '%d/%m/%Y %H:%i:%S') AS data_pagamento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                AND C.valor_pago is not null;
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['valor_pago'] = "Valor Pago";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";
        $header['data_pagamento'] = "Dt Pagamento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'
                   AND C.valor_pago is not null";

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;

    }elseif ($tipo == "aPagar"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                AND C.valor_pago is null
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'
                   AND C.valor_pago is null";

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;
    }
}else{
    if ($tipo == "todos"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                CONCAT('R$ ',REPLACE(CAST(C.valor_pago as decimal(15,2)), '.', ',')) AS valor_pago,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento,
                DATE_FORMAT(C.data_pagamento, '%d/%m/%Y %H:%i:%S') AS data_pagamento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                AND C.id_cliente = ".$idCli['id']."
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['valor_pago'] = "Valor Pago";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";
        $header['data_pagamento'] = "Dt Pagamento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'
                   AND C.id_cliente = ".$idCli['id'];

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;
    }elseif ($tipo == "pago"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                CONCAT('R$ ',REPLACE(CAST(C.valor_pago as decimal(15,2)), '.', ',')) AS valor_pago,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento,
                DATE_FORMAT(C.data_pagamento, '%d/%m/%Y %H:%i:%S') AS data_pagamento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                AND C.id_cliente = ".$idCli['id']."
                AND C.data_pagamento is not null
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['valor_pago'] = "Valor Pago";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";
        $header['data_pagamento'] = "Dt Pagamento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'
                   AND C.data_pagamento is not null
                   AND C.id_cliente = ".$idCli['id'];

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;
    }elseif ($tipo == "aPagar"){
        $sql = "SELECT 
                L.nome,
                CONCAT('R$ ',REPLACE(CAST(C.valor_a_pagar as decimal(15,2)), '.', ',')) AS valor_a_pagar,
                DATE_FORMAT(C.data_inclusao, '%d/%m/%Y %H:%i:%S') AS data_inclusao,
                DATE_FORMAT(C.data_vencimento, '%d/%m/%Y') AS data_vencimento        
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                AND C.id_cliente = ".$idCli['id']."
                AND C.data_pagamento is null
                ORDER BY C.id_cliente, C.data_inclusao
                LIMIT $itemInicial, 10";

        $header['nome'] = "Nome";
        $header['valor_a_pagar'] = "Valor a Pagar";
        $header['data_inclusao'] = "Dt Venda";
        $header['data_vencimento'] = "Dt Vencimento";

        //pagination
        $sqlPag = "SELECT count(*) as qtd FROM crediario C, cliente L
                   WHERE C.id_cliente = L.id
                   AND C.data_inclusao between '$dtIni' AND '$dtFim'
                   AND C.data_pagamento is null
                   AND C.id_cliente = ".$idCli['id'];

        try{
            $sqlPag = $pdo->query($sqlPag)->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $quantidadePaginas = (float)$sqlPag['qtd']/10;
        if ($quantidadePaginas > intval($quantidadePaginas)){
            $quantidadePaginas = intval($quantidadePaginas)+1;
        }else{
            $quantidadePaginas = intval($quantidadePaginas);
        }

        $pagination['pagina'] = intval($pagina);
        $pagination['qtdPaginas'] = $quantidadePaginas;
    }
}

//proximos comandos para retornornar dados para o relatorio
try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
  echo "Erro: ".$e->getMessage();
}

if ($sql->rowCount() > 0) {
    $return = $sql->fetchAll();
    $return[count($return)] = $header;
    $return[count($return)] = $pagination;
    print_r(json_encode($return));
}else{
    echo "false";
}
?>