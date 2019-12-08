<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT id, nome FROM fornecedor WHERE nome = 'dasdasdww' AND data_exclusao is null";
}elseif(is_numeric($pesquisa) && strlen($pesquisa)==14){
    $sql = "SELECT id, nome FROM fornecedor WHERE cnpj = '$pesquisa' AND data_exclusao is null";
}elseif (is_numeric($pesquisa) && strlen($pesquisa)!=14){
    $sql = "SELECT id, nome FROM fornecedor WHERE id = '$pesquisa' AND data_exclusao is null";
}else{
    $sql = "SELECT id, nome FROM fornecedor WHERE nome like '$pesquisa%' AND data_exclusao is null";
}

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($sql->fetchAll());

echo $result;