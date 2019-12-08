<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT * FROM produto WHERE nome = 'dasdasdww'";
}elseif(is_numeric($pesquisa) && (strlen($pesquisa)==14 || strlen($pesquisa)==13)){
    $sql = "SELECT 
            P.id as id,
            P.nome as nome,
            P.cod_barra as cod_barra,
            P.quantidade as quantidade,
            P.id_unidade as id_unidade,
            U.nome as unidade,
            U.sigla as sigla,
            P.id_fornecedor as id_fornecedor,
            P.id_grupo as id_grupo,
            P.id_classe as id_classe,
            P.id_subclasse as id_subclasse,
            P.valor_compra as valor_compra,
            P.valor_venda as valor_venda
            FROM produto P, unidade U
            WHERE P.cod_barra = '$pesquisa'
            AND P.id_unidade = U.id
            AND P.data_exclusao is null";
}elseif (is_numeric($pesquisa) && (strlen($pesquisa)!=14 || strlen($pesquisa)!=13)){
    $sql = "SELECT 
            P.id as id,
            P.nome as nome,
            P.cod_barra as cod_barra,
            P.quantidade as quantidade,
            P.id_unidade as id_unidade,
            U.nome as unidade,
            U.sigla as sigla,
            P.id_fornecedor as id_fornecedor,
            P.id_grupo as id_grupo,
            P.id_classe as id_classe,
            P.id_subclasse as id_subclasse,
            P.valor_compra as valor_compra,
            P.valor_venda as valor_venda
            FROM produto P, unidade U
            WHERE P.id = '$pesquisa'
            AND P.id_unidade = U.id
            AND P.data_exclusao is null";
}else{
    $sql = "SELECT 
            P.id as id,
            P.nome as nome,
            P.cod_barra as cod_barra,
            P.quantidade as quantidade,
            P.id_unidade as id_unidade,
            U.nome as unidade,
            U.sigla as sigla,
            P.id_fornecedor as id_fornecedor,
            P.id_grupo as id_grupo,
            P.id_classe as id_classe,
            P.id_subclasse as id_subclasse,
            P.valor_compra as valor_compra,
            P.valor_venda as valor_venda
            FROM produto P, unidade U
            WHERE P.nome like '$pesquisa%'
            AND P.id_unidade = U.id
            AND P.data_exclusao is null";
}

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($sql->fetchAll());

echo $result;