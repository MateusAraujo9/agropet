<?php
require "conection.php";

$numCaixa = isset($_GET['ncaixa'])?$_GET['ncaixa']:"";

//--- Informações do caixa (numero, data de abertura e fechamento)
//--- Abertura
$sqlInfo = "SELECT 
            id, 
            DATE_FORMAT(data_abertura, '%d/%m/%Y %H:%i:%S') AS dt_abertura,
            DATE_FORMAT(data_fechamento, '%d/%m/%Y %H:%i:%S') AS dt_fechamento,
            CONCAT('R$ ',REPLACE(CAST(((valor_abertura_moeda)/COUNT(*)) as decimal(15,2)), '.', ',')) AS abertura_moeda,
            CONCAT('R$ ',REPLACE(CAST(((SUM(valor_abertura_cedula))/COUNT(*)) as decimal(15,2)), '.', ',')) AS abertura_cedula
            FROM caixa WHERE id = $numCaixa";

try{
    $sqlInfo = $pdo->query($sqlInfo);
}catch (PDOException $e){
    $e->getMessage();
}

if ($sqlInfo->rowCount() > 0){
    $sqlInfo = $sqlInfo->fetch();
}

//--- Valores por especie
$sqlEspec = "SELECT 
             E.nome,
             CONCAT('R$ ',REPLACE(CAST(SUM(V.valor_total) as decimal(15,2)), '.', ',')) AS total_especie 
             FROM venda V, especie E
             WHERE V.id_caixa = $numCaixa
             AND V.tipo_venda = 'V' 
             AND V.id_especie = E.id
             GROUP BY V.id_especie";

try{
    $retornoEspec = $pdo->query($sqlEspec);
}catch (PDOException $e){
    $e->getMessage();
}

if ($retornoEspec->rowCount() >0){
    $retornoEspec = $retornoEspec->fetchAll();
}else{
    $retornoEspec = [
        "0" =>[
            "nome"=> "Nenhuma venda realizada",
            "total_especie"=> 0
        ]
    ];
}
//--- Crediario recebido
$sqlCred = "SELECT 
            CASE
            WHEN R.numero_venda IS NULL THEN CONCAT(C.nome,' - Crediario Parcial') 
            ELSE CONCAT(C.nome,' - Nº Venda', R.numero_venda) END AS descricao,
            CONCAT('R$ ',REPLACE(CAST(R.valor_pago as decimal(15,2)), '.', ',')) AS vl_pago
            FROM movimentacao_caixa M, crediario R, cliente C
            WHERE M.id_caixa = $numCaixa
            AND M.id_crediario = R.id
            AND R.id_cliente = C.id";

try{
    $sqlCred = $pdo->query($sqlCred);
}catch (PDOException $e){
    $e->getMessage();
}
if ($sqlCred->rowCount()>0){
    $sqlCred = $sqlCred->fetchAll();
}else{
    $sqlCred = [
        "0" => [
            "descricao" => "Nenhum crediário recebido.",
            "vl_pago" => 0
        ]
    ];
}
//--- Informações gerenciais
//  -- Ticket médio
$sqlTick = "SELECT 
            CONCAT('R$ ',REPLACE(CAST(((SUM(valor_total))/COUNT(*)) as decimal(15,2)), '.', ',')) AS ticket_medio
            FROM venda
            WHERE id_caixa = $numCaixa
            AND tipo_venda = 'V'";

try{
    $sqlTick = $pdo->query($sqlTick);
}catch(PDOException $e){
    $e->getMessage();
}

if ($sqlTick->rowCount() > 0){
    $sqlTick = $sqlTick->fetch();
}

//  -- Quantidade de clientes atendidos
$sqlQtdCli = "SELECT 
              COUNT(*) as qtdCli
              FROM venda
              WHERE id_caixa = $numCaixa
              AND tipo_venda = 'V'
              AND id_cliente IS NOT NULL";

try{
    $sqlQtdCli = $pdo->query($sqlQtdCli);
}catch (PDOException $e){
    $e->getMessage();
}

if ($sqlQtdCli->rowCount()>0){
    $sqlQtdCli = $sqlQtdCli->fetch();
}

//  -- Valor total vendido, CONSULTA FUDIDA, PARA ENTENDER, SEPARAR EM PARTES

$sqlVlTot = "SELECT 
            CONCAT('R$ ',REPLACE(CAST((TB_C.valor_abertura - TB_C.valor_retirada + TB_C.valor_deposito + TB_C.valor_crediario + TB_C.vlTot) as decimal(15,2)), '.', ',')) AS vl_total
            FROM
            (SELECT 
            (C1.valor_abertura_cedula + C1.valor_abertura_moeda) AS valor_abertura,
            retirada.valor_retirada, 
            deposito.valor_deposito,
            tb_crediario.valor_crediario,
            CASE
            WHEN VE.vlTot IS NULL THEN 0
            ELSE VE.vlTot END AS vlTot,
            C1.id
            FROM
            caixa C1,
            (SELECT SUM(R.valor), 
            CASE
            WHEN R.valor IS NULL THEN 0
            ELSE SUM(R.valor) END AS valor_retirada,
            R.id_caixa FROM movimentacao_caixa R
            WHERE R.id_caixa = :nCaixa
            AND R.tipo = 'R') AS retirada,
            (SELECT 
            SUM(D.valor), 
            CASE
            WHEN D.valor IS NULL THEN 0
            ELSE SUM(D.valor) END AS valor_deposito,
            D.id_caixa  
            FROM movimentacao_caixa D
            WHERE D.id_caixa = :nCaixa
            AND D.tipo = 'D') AS deposito,
            (SELECT 
            SUM(crediario.valor_pago),
            CASE
            WHEN crediario.valor_pago IS NULL THEN 0
            ELSE SUM(crediario.valor_pago) END AS valor_crediario, 
            CR.id_caixa 
            FROM movimentacao_caixa CR
            JOIN crediario ON crediario.id = CR.id_crediario
            WHERE CR.id_caixa = :nCaixa
            AND CR.tipo NOT IN ('R', 'D')) AS tb_crediario
            LEFT JOIN
            (SELECT SUM(V.valor_total)AS vlTot, 
            V.id_caixa
            FROM venda V
            WHERE V.id_caixa = :nCaixa
            AND V.tipo_venda = 'V'
            GROUP BY V.id_caixa) VE ON VE.id_caixa = :nCaixa
            WHERE C1.id = :nCaixa) TB_C";
$sqlVlTot = $pdo->prepare($sqlVlTot);
$sqlVlTot->bindValue(":nCaixa", $numCaixa);
$sqlVlTot->execute();

if ($sqlVlTot->rowCount()>0){
    $sqlVlTot = $sqlVlTot->fetch();
}

//  -- Valor de desconto
$sqlDesc = "SELECT 
            CONCAT('R$ ',REPLACE(CAST(sum((I.desconto/100)*I.valor_total) as decimal(15,2)), '.', ',')) AS valor_desconto
            FROM venda V, itens_venda I
            WHERE V.id_caixa = $numCaixa
            AND V.tipo_venda = 'V'
            AND I.desconto > 0
            AND V.id = I.id_venda";

try{
    $sqlDesc = $pdo->query($sqlDesc);
}catch (PDOException $e){
    $e->getMessage();
}

if($sqlDesc->rowCount()>0){
    $sqlDesc = $sqlDesc->fetch();
}

//Depositos
$sqlDeposito = "SELECT 
                descricao, 
                CONCAT('R$ ',REPLACE(CAST(valor as decimal(15,2)), '.', ',')) AS valor 
                FROM movimentacao_caixa
                WHERE id_caixa = $numCaixa
                AND tipo = 'D'";

try{
    $sqlDeposito = $pdo->query($sqlDeposito);
}catch (PDOException $e){
    $e->getMessage();
}

if ($sqlDeposito->rowCount() > 0){
    $sqlDeposito = $sqlDeposito->fetchAll();
}else{
    $sqlDeposito = [
        "0" => [
            "descricao" => "Nenhum depósito realizado.",
            "valor" => 0
        ]
    ];
}

//Retirada
$sqlRetirada = "SELECT 
                descricao, 
                CONCAT('R$ ',REPLACE(CAST(valor as decimal(15,2)), '.', ',')) AS valor 
                FROM movimentacao_caixa
                WHERE id_caixa = $numCaixa
                AND tipo = 'R'";

try{
    $sqlRetirada = $pdo->query($sqlRetirada);
}catch (PDOException $e){
    $e->getMessage();
}

if ($sqlRetirada->rowCount() > 0){
    $sqlRetirada = $sqlRetirada->fetchAll();
}else{
    $sqlRetirada = [
        "0" => [
            "descricao" => "Nenhuma retirada realizada.",
            "valor" => 0
        ]
    ];
}

//Preparando retorno
$return[0] = $sqlInfo;
$return[1] = $retornoEspec;
$return[2] = $sqlCred;
$return[3] = $sqlTick;
$return[4] = $sqlQtdCli;
$return[5] = $sqlVlTot;
$return[6] = $sqlDesc;
$return[7] = $sqlDeposito;
$return[8] = $sqlRetirada;

print_r(json_encode($return));


?>