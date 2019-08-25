<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";

if (empty($nome)){
    header("Location: /#!/listGrupo");
}else{
    $sql = "INSERT INTO grupo (nome) VALUES ('$nome')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listGrupo");
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
}