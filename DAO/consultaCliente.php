<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT id, nome FROM cliente WHERE nome = 'dasdasdww'";
}elseif(is_numeric($pesquisa) && strlen($pesquisa)==11){
    $sql = "SELECT id, nome FROM cliente WHERE cpf = '$pesquisa'";
}elseif (is_numeric($pesquisa) && strlen($pesquisa)!=11){
    $sql = "SELECT id, nome FROM cliente WHERE id = '$pesquisa'";
}else{
    $sql = "SELECT id, nome FROM cliente WHERE nome like '$pesquisa%'";
}

try{
    $retornoSql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($retornoSql->fetchAll());

print_r($result);