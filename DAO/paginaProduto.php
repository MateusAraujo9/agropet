<?php
require "conection.php";

$pagina = isset($_GET['pagina'])?$_GET['pagina']:"1";

$nrItem = $pagina*10-10;

$sql = "SELECT 
        P.id as id_produto,
        P.nome as nome_produto,
        P.cod_barra as barra,
        F.nome as nome_fornecedor,
        P.valor_compra as vlCompra,
        P.valor_venda as vlVenda      
        FROM produto P, fornecedor F
        WHERE P.id_fornecedor = F.id
        AND P.data_exclusao is null
        ORDER BY P.nome
        LIMIT ".$nrItem.", 10";

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

if ($sql->rowCount()>0){
    $resultado = json_encode($sql->fetchAll());
}

echo $resultado;

