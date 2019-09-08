<?php
require "conection.php";

$pesquisa = isset($_GET['pesquisa'])?$_GET['pesquisa']:null;

if ($pesquisa == null){
    echo "false";
}elseif (is_numeric($pesquisa)){
    $sql = "SELECT * FROM produto WHERE cod_barra = '$pesquisa'";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sql->rowCount()>0){
        $result = json_encode($sql->fetch());

        //print_r($result);
        echo $result;
    }else{
        $sql = "SELECT * FROM produto WHERE id = '$pesquisa'";

        try{
            $sql = $pdo->query($sql);
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        if ($sql->rowCount()>0){
            $result = json_encode($sql->fetch());

            //print_r($result);
            echo $result;
        }else{
            echo "false 3";
        }
    }
}else{
    $sql = "SELECT * FROM produto WHERE nome like '".$pesquisa."%'";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sql->rowCount()>0){
        $result = json_encode($sql->fetch());

        //print_r($result);
        echo $result;
    }else{
        echo "false 2";
    }
}
