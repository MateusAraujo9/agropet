<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT * FROM produto WHERE nome = 'dasdasdww'";
}elseif(is_numeric($pesquisa) && (strlen($pesquisa)==14 || strlen($pesquisa)==13)){
    $sql = "SELECT * FROM produto WHERE cod_barra = '$pesquisa'";
}elseif (is_numeric($pesquisa) && strlen($pesquisa)!=14){
    $sql = "SELECT * FROM produto WHERE id = '$pesquisa'";
}else{
    $sql = "SELECT * FROM produto WHERE nome like '$pesquisa%'";
}

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($sql->fetchAll());

echo $result;