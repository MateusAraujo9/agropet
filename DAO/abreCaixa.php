<?php
require "conection.php";

$cedula = isset($_GET['cedula'])?$_GET['cedula']:"false";
$moeda = isset($_GET['moeda'])?$_GET['moeda']:"0";

if ($cedula==="false") {
    echo "erro";
}else{
    $tkUser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"0";

    $sqlVerifica = $sql = "SELECT C.id, U.nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkUser' AND C.tipo = 'A'";

    try{
        $sqlVerifica = $pdo->query($sqlVerifica);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sqlVerifica->rowCount()===0){
        $sql = "SELECT * FROM usuario WHERE token = '$tkUser'";

        try{
            $sql = $pdo->query($sql);

            $sql = $sql->fetch();
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        $idUsuario = $sql['id'];

        $sqlC = "INSERT INTO caixa (id_usuario, tipo, valor_abertura_cedula, valor_abertura_moeda, data_abertura) 
                 VALUES ('$idUsuario', 'A', '$cedula', '$moeda', now())";

        try{
            $sqlC = $pdo->query($sqlC);
        }catch (PDOException $e){
            $e->getMessage();
        }

        //echo $sqlC->fetch();

        $sqlVerifica = $sql = "SELECT C.id as id, U.nome as nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkUser' AND C.tipo = 'A'";

        try{
            $sqlVerifica = $pdo->query($sqlVerifica);
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        $result = json_encode($sqlVerifica->fetch());
        echo $result;

    }else{
        echo "Caixa Aberto";
    }
}