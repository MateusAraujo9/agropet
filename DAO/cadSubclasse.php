<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";

if (empty($nome)){
    header("Location: /#!/listSubclasse");
}else{
    $sql = "INSERT INTO subclasse (nome) VALUES ('$nome')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listSubclasse");
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
}