<?php
require "conection.php";

$nome = isset($_POST['nome'])?$_POST['nome']:"";
$razao = isset($_POST['razao'])?$_POST['razao']:"";
$cnpj = isset($_POST['cnpj'])?$_POST['cnpj']:"";
$telefone = isset($_POST['tel'])?$_POST['tel']:"";
$email = isset($_POST['email'])?$_POST['email']:"";

if (empty($nome)){
    header("Location: /#!/listFornecedor");
}else{
    $sql = "INSERT INTO fornecedor (nome, razao, cnpj, telefone, email) VALUES ('$nome', '$razao', '$cnpj', '$telefone', '$email')";

    try{
        $sql = $pdo->query($sql);

        header("Location: /#!/listFornecedor");
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
}