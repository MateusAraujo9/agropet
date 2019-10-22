<?php
require "conection.php";

$tipo = isset($_GET['tipo'])?$_GET['tipo']:"";
$dtIni = isset($_GET['dtIni'])?$_GET['dtIni']:"";
$dtFim = isset($_GET['dtFim'])?$_GET['dtFim']:"";
$cliente = isset($_GET['cliente'])?$_GET['cliente']:"";
$pagina = isset($_GET['pagina'])?$_GET['pagina']:"";

$itemInicial = $pagina*10-10;

if ($cliente == ""){
    if ($tipo = "todos"){
        $sql = "SELECT 
                L.nome,
                C.valor_a_pagar,
                C.valor_pago,
                C.data_inclusao,
                C.data_vencimento,
                C.data_pagamento
                FROM crediario C, cliente L
                WHERE C.id_cliente = L.id
                AND C.data_inclusao between '$dtIni' AND '$dtFim'
                ORDER BY C.id_cliente
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

        $pagination['pagina'] = $pagina;
        $pagination['qtdPaginas'] = $quantidadePaginas;
    }elseif ($tipo = "pago"){

    }elseif ($tipo = "aPagar"){
        echo "";
    }
}else{
    if ($tipo = "todos"){

    }elseif ($tipo = "pago"){

    }elseif ($tipo = "aPagar"){
        echo "";
    }
}

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