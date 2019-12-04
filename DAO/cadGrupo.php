<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";

if (empty($nome)){
    echo "erro nome";
}else{
    $sql = "INSERT INTO grupo (nome) VALUES ('$nome')";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sql == false){
        echo "erro inesperado";
    }
}