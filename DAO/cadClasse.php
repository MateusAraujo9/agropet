<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";

if (empty($nome)){
    //header("Location: /#!/listClasse");
    echo "erro nome";
}else{
    $sql = "INSERT INTO classe (nome) VALUES ('$nome')";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if($sql == false){
      echo "erro inesperado";
    }
}