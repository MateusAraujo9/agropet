<?php
require "conection.php";

$dtIni = isset($_GET['dtIni'])?$_GET['dtIni']:"";
$dtFim = isset($_GET['dtFim'])?$_GET['dtFim']:"";
$soCliente = isset($_GET['soCliente'])?$_GET['soCliente']:"";
$porItem = isset($_GET['porItem'])?$_GET['porItem']:"";
$cliente = isset($_GET['cliente'])?$_GET['cliente']:"";
$pagina = isset($_GET['pagina'])?$_GET['pagina']:"";

$itemInicial = $pagina*10-10;

if ($dtIni != "" && $dtFim != ""){

    if ($cliente != ""){

        //Vendas de um cliente especifico, podendo ser por item ou por venda

        //Pegar id do cliente
        $sqlCli = "SELECT id FROM  cliente WHERE nome = '$cliente'";
        try{
            $sqlCli = $pdo->query($sqlCli);
            $sqlCli = $sqlCli->fetch();
        }catch (PDOException $e){
            $e->getMessage();
        }

        $idCliente = $sqlCli['id'];

        if ($porItem === "false"){
            //relatorio por venda de um cliente especifico
            $sqlVendas = "SELECT 
                          V.id AS nVenda,
                          C.nome,
                          CONCAT('R$ ',REPLACE(CAST(V.valor_total as decimal(15,2)), '.', ',')) AS valor_total,
                          DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda
                          FROM venda V, especie E, cliente C
                          WHERE V.id_cliente = $idCliente  
                          AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                          AND V.id_especie = E.id
                          AND V.id_cliente = C.id
                          ORDER BY V.id
                          LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT 
                            CONCAT('R$ ',REPLACE(CAST(SUM(V.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V, especie E, cliente C
                            WHERE V.id_cliente = $idCliente 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.id_especie = E.id
                            AND V.id_cliente = C.id";

            $header['nVenda'] = "Número da Venda";
            $header['nome'] = "Cliente";
            $header['valor_total'] = "Valor Total";
            $header['data_venda'] = "Dt da Venda";

            //pagination
            $sqlPag = "SELECT COUNT(*) as qtd FROM venda V
                              WHERE V.id_cliente = $idCliente 
                              AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";

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

        }else{
            //Relatorio por item de cliente especifico
            $sqlVendas = "SELECT 
                          V.id as nVenda, 
                          P.nome as produto,
                          DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda,
                          I.quantidade as quantidade,
                          CONCAT('R$ ',REPLACE(CAST(I.valor_liquido_unitario as decimal(15,2)), '.', ',')) AS vlLiquido,
                          CONCAT('R$ ',REPLACE(CAST(I.valor_bruto as decimal(15,2)), '.', ',')) AS vlBruto,
                          CONCAT(I.desconto, '%') AS desconto,
                          CONCAT('R$ ',REPLACE(CAST(I.valor_total as decimal(15,2)), '.', ',')) AS vlTotal
                          FROM venda V, itens_venda I, produto P 
                          WHERE V.id = I.id_venda
                          AND I.id_produto = P.id 
                          AND V.id_cliente = $idCliente 
                          AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                          ORDER BY V.id
                          LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT 
                            CONCAT('R$ ',REPLACE(CAST(SUM(I.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V, itens_venda I, produto P 
                            WHERE V.id = I.id_venda
                            AND I.id_produto = P.id 
                            AND V.id_cliente = $idCliente 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";

            $header['nVenda'] = "Número da Venda";
            $header['produto'] = "Produto";
            $header['data_venda'] = "Dt da Venda";
            $header['quantidade'] = "Quantidade";
            $header['vlLiquido'] = "Vl Liquido";
            $header['vlBruto'] = "Vl Bruto";
            $header['desconto'] = "Desconto %";
            $header['vlTotal'] = "Vl Total";

            //pagination
            $sqlPag = "SELECT COUNT(*) as qtd FROM venda V, itens_venda I
                       WHERE V.id = I.id_venda
                       AND V.id_cliente = $idCliente 
                       AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";

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

    }else if ($soCliente === "true"){
        if ($porItem === "false"){
            //Vendas só com clientes, por venda
            $sqlVendas = "SELECT
                            V.id AS nVenda,
                            C.nome AS cliente,
                            CONCAT('R$ ',REPLACE(CAST(V.valor_total as decimal(15,2)), '.', ',')) AS valor_total,
                            DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda,
                            E.nome AS especie
                            FROM venda V, cliente C, especie E 
                            WHERE V.id_cliente is not NULL 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.id_cliente = C.id
                            AND V.id_especie = E.id
                            ORDER BY V.id
                            LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT
                            CONCAT('R$ ',REPLACE(CAST(SUM(V.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V, cliente C, especie E 
                            WHERE V.id_cliente is not NULL 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.id_cliente = C.id
                            AND V.id_especie = E.id";

            $header['nVenda'] = "Número da Venda";
            $header['cliente'] = "Cliente";
            $header['valor_total'] = "Valor Total";
            $header['data_venda'] = "Data da Venda";
            $header['especie'] = "Especie";

            //pagination
            $sqlPag = "SELECT COUNT(*) as qtd FROM venda V
                        WHERE V.id_cliente is not NULL 
                        AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'";

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

        }else{
            $sqlVendas = "SELECT
                            V.id AS nVenda,
                            C.nome AS cliente,
                            P.nome AS produto,
                            I.quantidade,
                            CONCAT('R$ ',REPLACE(CAST(I.valor_total as decimal(15,2)), '.', ',')) AS valor_total,
                            DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda,
                            CONCAT(I.desconto, '%') AS desconto
                            FROM venda V, cliente C, especie E, itens_venda I, produto P
                            WHERE V.id_cliente is not NULL 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.id_cliente = C.id
                            AND V.id_especie = E.id
                            AND V.id = I.id_venda
                            AND I.id_produto = P.id
                            ORDER BY V.id, I.id
                            LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT
                            CONCAT('R$ ',REPLACE(CAST(SUM(I.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V, cliente C, especie E, itens_venda I, produto P
                            WHERE V.id_cliente is not NULL 
                            AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.id_cliente = C.id
                            AND V.id_especie = E.id
                            AND V.id = I.id_venda
                            AND I.id_produto = P.id
                            AND V.tipo_venda = 'V'";

            $header['nVenda'] = "Número da Venda";
            $header['cliente'] = "Cliente";
            $header['produto'] = "Produto";
            $header['quantidade'] = "Quantidade";
            $header['valor_total'] = "Valor Total";
            $header['data_venda'] = "Data da Venda";
            $header['desconto'] = "Desconto";

            //pagination
            $sqlPag = "SELECT COUNT(*) as qtd FROM venda V, itens_venda I
                        WHERE V.id_cliente is not NULL 
                        AND V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                        AND V.id = I.id_venda";

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
        if ($porItem === "false"){
            $sqlVendas = "SELECT
                            V.id AS nVenda,
                            C.nome AS cliente,
                            CONCAT('R$ ',REPLACE(CAST(V.valor_total as decimal(15,2)), '.', ',')) AS valor_total,
                            DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda,
                            E.nome AS especie
                            FROM venda V
                            LEFT JOIN cliente C ON C.id = V.id_cliente
                            JOIN especie E ON V.id_especie = E.id
                            WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.tipo_venda = 'V'
                            ORDER BY V.id
                            LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT
                            CONCAT('R$ ',REPLACE(CAST(SUM(V.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V
                            LEFT JOIN cliente C ON C.id = V.id_cliente
                            JOIN especie E ON V.id_especie = E.id
                            WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.tipo_venda = 'V'";

            $header['nVenda'] = "Número da Venda";
            $header['cliente'] = "Cliente";
            $header['valor_total'] = "Valor Total";
            $header['data_venda'] = "Data da Venda";
            $header['especie'] = "Especie";

            //pagination
            $sqlPag = "SELECT
                        COUNT(*) as qtd
                        FROM venda V
                        LEFT JOIN cliente C ON C.id = V.id_cliente
                        JOIN especie E ON V.id_especie = E.id
                        WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                        AND V.tipo_venda = 'V'";

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
        }else{
            $sqlVendas = "SELECT
                            V.id AS nVenda,
                            C.nome AS cliente,
                            P.nome AS produto,
                            I.quantidade,
                            CONCAT('R$ ',REPLACE(CAST(I.valor_total as decimal(15,2)), '.', ',')) AS valor_total,
                            DATE_FORMAT(V.data_venda, '%d/%m/%Y %H:%i:%S') AS data_venda,
                            CONCAT(I.desconto, '%') AS desconto
                            FROM venda V
                            LEFT JOIN cliente C ON C.id = V.id_cliente
                            JOIN especie E ON V.id_especie = E.id
                            JOIN itens_venda I ON V.id = I.id_venda
                            JOIN produto P ON I.id_produto = P.id
                            WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.tipo_venda = 'V'
                            ORDER BY V.id, I.id
                            LIMIT $itemInicial, 10";

            $sqlSubTotal = "SELECT
                            CONCAT('R$ ',REPLACE(CAST(SUM(I.valor_total) as decimal(15,2)), '.', ',')) AS subtotal
                            FROM venda V
                            LEFT JOIN cliente C ON C.id = V.id_cliente
                            JOIN especie E ON V.id_especie = E.id
                            JOIN itens_venda I ON V.id = I.id_venda
                            JOIN produto P ON I.id_produto = P.id
                            WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                            AND V.tipo_venda = 'V'";

            $header['nVenda'] = "Número da Venda";
            $header['cliente'] = "Cliente";
            $header['produto'] = "Produto";
            $header['quantidade'] = "Quantidade";
            $header['valor_total'] = "Valor Total";
            $header['data_venda'] = "Data da Venda";
            $header['desconto'] = "Desconto";

            //pagination
            $sqlPag = "SELECT
                        COUNT(*) as qtd
                        FROM venda V
                        LEFT JOIN cliente C ON C.id = V.id_cliente
                        JOIN especie E ON V.id_especie = E.id
                        JOIN itens_venda I ON V.id = I.id_venda
                        JOIN produto P ON I.id_produto = P.id
                        WHERE V.data_venda BETWEEN '$dtIni 00:00:00' AND '$dtFim 23:59:59'
                        AND V.tipo_venda = 'V'";

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
}

//Realizando consulta
try{
    $retorno = $pdo->query($sqlVendas);
    $ret1 = $pdo->query($sqlSubTotal);
}catch (PDOException $e){
    $e->getMessage();
}

if ($retorno->rowCount() > 0  && $ret1->rowCount() > 0){
    $retorno = $retorno->fetchAll();
    $ret1 = $ret1->fetch();
    $retorno[count($retorno)] = $header;
    $retorno[count($retorno)] = $pagination;
    $retorno[count($retorno)] = $ret1;
    print_r(json_encode($retorno));
}else{
    echo $sqlVendas;
}

