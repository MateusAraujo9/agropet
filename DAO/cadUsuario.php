<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";
$login = isset($_POST['login'])?$_POST['login']:"";
$senha = isset($_POST['senha'])?$_POST['senha']:"";

if (!empty($nome) && !empty($login) && !empty($senha)){
    $senha = md5($senha);
    $token = md5($nome.$login.$senha.random_int(1, 99999));

    $sql = "INSERT INTO usuario (nome, login, senha, token) VALUES ('".$nome."', '".$login."', '".$senha."', '".$token."')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listUsuario");
    }catch (PDOException $e){
        echo "Erro de conexão: ".$e->getMessage();
    }
}else{
    header("Location: /#!/listUsuario");
}