<?php
require "conection.php";

$idUser = isset($_POST['idUsuario'])?$_POST['idUsuario']:"";
$novaSenha = isset($_POST['novaSenha'])?$_POST['novaSenha']:"";
$tkUser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"";

if (empty($idUser) || empty($novaSenha)){
    echo "<script>";
    echo "alert('Passado algum campo vazio. \\n $idUser \\n $novaSenha');";
    echo "window.location.href = \"/#!/listUsuario\"";
    echo "</script>";

}else{
    $sql = "SELECT * FROM usuario WHERE token = '".$tkUser."'";
    $novaSenha = md5($novaSenha);

    try{
        $sql = $pdo->query($sql);

        $usuarioLogado = $sql->fetch();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($usuarioLogado['id'] == '1'){
        $sql = "UPDATE usuario SET senha = '".$novaSenha."' WHERE id = $idUser";

        try{
            $sql = $pdo->query($sql);
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        header("Location: /#!/listUsuario");
    }elseif ($usuarioLogado['id'] == $idUser){
        $sql = "UPDATE usuario SET senha = '".$novaSenha."' WHERE id = $idUser";

        try{
            $sql = $pdo->query($sql);
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        header("Location: /#!/listUsuario");
    }else{
        echo "<script>";
        echo "alert('Não pode alterar a senha de outro usuário sem ser administrador');";
        echo "window.location.href = \"/#!/listUsuario\"";
        echo "</script>";

    }

}