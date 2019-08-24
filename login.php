<?php
require "DAO/conection.php";
$login = isset($_POST['login'])?$_POST['login']:"";
$senha = md5(isset($_POST['password'])?$_POST['password']:"");
$logado = isset($_COOKIE['logado'])?$_COOKIE['logado']:"";

if ($logado == 'true'){
    header("Location: /index.php");
}

if (!empty($login)){
    $sql = "SELECT * FROM usuario WHERE login = '".$login."' AND senha = '".$senha."'";

    try{
        $sql = $pdo->query($sql);

        if ($sql->rowCount() > 0){
            $usuario = $sql->fetch();

            setcookie("tkuser", $usuario['token'], time()+18000);
            setcookie("logado", "true", time()+18000);
            //echo "<script>alert('Login correto: ".$usuario['token']."')</script>";

            header("Location: /index.php");
        }else{
            echo "<script>alert('Usuário ou Senha incorreto.')</script>";
        }
    }catch (PDOException $e){
        echo "Erro de conexão: ".$e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agropet - Casa do Criador</title>

    <!--Bootstrap-->
    <link rel="stylesheet" href="resourse/css/bootstrap.css">

    <!--React-->
    <script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>

    <!--Meus links-->
    <link rel="stylesheet" href="resourse/css/style.css">
    <link rel="stylesheet" href="resourse/css/style-index.css">
    <script src="resourse/js/script.js"></script>
    <meta charset="utf-8">
</head>
<body>
<img src="resourse/imagens/Sua-Logo-768x576.jpg" alt="pet">
<form class="formLogin" method="post">
    <div class="card border-primary mb-3" style="max-width: 20rem;">
        <div class="card-header">Acessar</div>
        <div class="card-body">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Login" id="login" name="login">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" placeholder="Password" name="password">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Entrar">
            </div>
        </div>
    </div>
</form>
</body>
</html>