<?php
require "DAO/conection.php";
$pagina = isset($_GET['pagina'])?$_GET['pagina']:"";
$tkuser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"";

if (!empty($pagina)){
    $sql = "SELECT * FROM usuario WHERE token = '$tkuser'";

    try{
        $sql = $pdo->query($sql);

        $usuario = $sql->fetch();


    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    if ($sql->rowCount() > 0){
        $sql2 = "SELECT * FROM configuracoes WHERE nome = 'paginaCliente' AND usuario = '".$usuario['id']."'";

        try{
            $sql2 = $pdo->query($sql2);
        }catch (PDOException $e){
            echo "Erro: ".$e->getMessage();
        }

        if ($sql2->rowCount() > 0){
            $sql3 = "UPDATE configuracoes SET valor = '$pagina' WHERE nome = 'paginaCliente' AND usuario = '".$usuario['id']."'";

            try{
                $pdo->query($sql3);
            }catch (PDOException $e){
                echo "Erro: ".$e->getMessage();
            }
        }else{
            $sql3 = "INSERT INTO configuracoes (nome, valor, usuario) VALUES ('paginaCliente', '$pagina', '".$usuario['id']."')";

            try{
                $pdo->query($sql3);
            }catch (PDOException $e){
                echo "Erro: ".$e->getMessage();
            }
        }
    }
};

?>
