<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:"";

if ($pesquisa == null || empty($pesquisa)){
    $sql = "SELECT id, nome FROM fornecedor WHERE nome = 'dasdasdww'";
}elseif(is_numeric($pesquisa) && strlen($pesquisa)==14){
    $sql = "SELECT id, nome FROM fornecedor WHERE cnpj = '$pesquisa'";
}elseif (is_numeric($pesquisa) && strlen($pesquisa)!=14){
    $sql = "SELECT id, nome FROM fornecedor WHERE id = '$pesquisa'";
}else{
    $sql = "SELECT id, nome FROM fornecedor WHERE nome like '$pesquisa%'";
}

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$result = json_encode($sql->fetchAll());

echo $result;