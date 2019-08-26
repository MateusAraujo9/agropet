<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";
$cpf = isset($_POST['cpf'])?$_POST['cpf']:"";
$time = isset($_POST['dtNasc'])?strtotime($_POST['dtNasc']):"";
$estado = isset($_POST['estado'])?$_POST['estado']:"";
$cidade = isset($_POST['cidade'])?$_POST['cidade']:"";
$bairro = isset($_POST['bairro'])?$_POST['bairro']:"";
$rua = isset($_POST['rua'])?$_POST['rua']:"";
$cep = isset($_POST['cep'])?$_POST['cep']:"";
$numero = isset($_POST['numero'])?$_POST['numero']:"";
$tel = isset($_POST['tel'])?$_POST['tel']:"";
$email = isset($_POST['email'])?$_POST['email']:"";


if($time){
    $dtNasc = date('Y.m.d',$time);
}

if (empty($nome) || empty($cpf)){
    echo "<script>";
    echo "alert('Não foi informado nome ou cpf. Cliente Não cadastrado');";
    echo "window.location.href = \"/#!/listCliente\"";
    echo "</script>";
}else{
    //echo "<br>$nome<br>$cpf<br>$time<br>$estado<br>$cidade<br>$bairro<br>$rua<br>$numero<br>$tel<br>$email";

    $sql = "INSERT INTO cliente (nome, cpf, dataNasc, cidade, estado, bairro, rua, numero, telefone, email, cep) VALUES ('$nome', '$cpf', '$dtNasc', '$cidade', '$estado', '$bairro', '$rua', '$numero', '$tel', '$email', '$cep')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listCliente");
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }




}