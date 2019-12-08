<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT id, nome, valor_comprado FROM cliente WHERE nome = 'dasdasdww' AND data_exclusao is null";
}elseif(is_numeric($pesquisa) && strlen($pesquisa)==11){
    $sql = "SELECT id, nome, valor_comprado FROM cliente WHERE cpf = '$pesquisa' AND data_exclusao is null";
}elseif (is_numeric($pesquisa) && strlen($pesquisa)!=11){
    $sql = "SELECT id, nome, valor_comprado FROM cliente WHERE id = '$pesquisa' AND data_exclusao is null";
}else{
    $sql = "SELECT id, nome, valor_comprado FROM cliente WHERE nome like '$pesquisa%' AND data_exclusao is null";
}

try{
    $retornoSql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($retornoSql->fetchAll());

print_r($result);