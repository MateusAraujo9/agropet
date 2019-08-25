<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";

if (empty($nome)){
    header("Location: /#!/listClasse");
}else{
    $sql = "INSERT INTO classe (nome) VALUES ('$nome')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listClasse");
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
}