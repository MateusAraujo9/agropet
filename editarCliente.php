<?php
require "DAO/conection.php";

/*
 * Para carregar tela de edição
 * */
$idCliente = isset($_GET['cliente'])?$_GET['cliente']:"";

if (!empty($idCliente)){
    $sql = "SELECT * FROM cliente WHERE id = '$idCliente'";

    try{
        $sql = $pdo->query($sql);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    $cliente = $sql->fetch();
}

/*
 * Recebe dados alterados via post e redireciona para cadastro de cliente.
 * */

$idClientePost = isset($_POST['idCliente'])?$_POST['idCliente']:"";
$nomeCliente = isset($_POST['nomeCliente'])?$_POST['nomeCliente']:"";
$cpfCliente = isset($_POST['cpfCliente'])?$_POST['cpfCliente']:"";
$nascCliente = isset($_POST['nascCliente'])?$_POST['nascCliente']:"";
$cidadeCliente = isset($_POST['cidadeCliente'])?$_POST['cidadeCliente']:"";
$estadoCliente = isset($_POST['estadoCliente'])?$_POST['estadoCliente']:"";
$bairroCliente = isset($_POST['bairroCliente'])?$_POST['bairroCliente']:"";
$ruaCliente = isset($_POST['ruaCliente'])?$_POST['ruaCliente']:"";
$numeroCliente = isset($_POST['numeroCliente'])?$_POST['numeroCliente']:"";
$telefoneCliente = isset($_POST['telefoneCliente'])?$_POST['telefoneCliente']:"";
$emailCliente = isset($_POST['emailCliente'])?$_POST['emailCliente']:"";
$cepCliente = isset($_POST['cepCliente'])?$_POST['cepCliente']:"";

if (!empty($idClientePost)){
    $sql2 = "UPDATE cliente SET 
              nome = '$nomeCliente', 
              cpf = '$cpfCliente',
              dataNasc = '$nascCliente',
              cidade = '$cidadeCliente',
              estado = '$estadoCliente',
              bairro = '$bairroCliente',
              rua = '$ruaCliente',
              numero = '$numeroCliente',
              telefone = '$telefoneCliente',
              email = '$emailCliente',
              cep = '$cepCliente'
              WHERE id = '$idClientePost'";

    try{
        $sql2 = $pdo->query($sql2);
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    header("Location: http://agropet.pc/#!/listCliente/");
}


?>

<head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="/resourse/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/resourse/css/style.css">
</head>
<h3 class="titulo">Editar Cliente</h3>

<div class="container">
    <div class="containerCenter2">
        <a class="btn btn-outline-primary btn-sm" href="http://agropet.pc/#!/listCliente/"><img src="resourse/imagens/voltarP.png" class="imgVoltarP" alt="voltar">Voltar</a>
        <form method="post">
            <div class="form-group row">
                <fieldset class="col-md-2">
                    <label class="col-form-label" for="idCliente">Id</label>
                    <input class="form-control" id="idCliente" name="idCliente" type="text" value="<?php echo $cliente['id']?>" readonly="">
                </fieldset>
                <fieldset class="col-md-10">
                    <label class="col-form-label" for="nomeCliente">Nome</label>
                    <input type="text" class="form-control" id="nomeCliente" name="nomeCliente" value="<?php echo $cliente['nome']?>">
                </fieldset>
            </div>
            <div class="form-group row">
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="cpfCliente">CPF</label>
                    <input type="text" class="form-control" id="cpfCliente" name="cpfCliente" value="<?php echo $cliente['cpf']?>">
                </fieldset>
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="nascCliente">Data Nascimento</label>
                    <input type="date" class="form-control" id="nascCliente" name="nascCliente" value="<?php echo $cliente['dataNasc']?>">
                </fieldset>
                <fieldset class="col-md-6">
                    <label class="col-form-label" for="cidadeCliente">Cidade</label>
                    <input type="text" class="form-control" id="cidadeCliente" name="cidadeCliente" value="<?php echo $cliente['cidade']?>">
                </fieldset>
            </div>
            <div class="form-group row">
                <fieldset class="col-md-6">
                    <label class="col-form-label" for="estadoCliente">Estado</label>
                    <input type="text" class="form-control" id="estadoCliente" name="estadoCliente" value="<?php echo $cliente['estado']?>">
                </fieldset>
                <fieldset class="col-md-6">
                    <label class="col-form-label" for="bairroCliente">Bairro</label>
                    <input type="text" class="form-control" id="bairroCliente" name="bairroCliente" value="<?php echo $cliente['bairro']?>">
                </fieldset>
            </div>
            <div class="form-group row">
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="ruaCliente">Rua</label>
                    <input type="text" class="form-control" id="ruaCliente" name="ruaCliente" value="<?php echo $cliente['rua']?>">
                </fieldset>
                <fieldset class="col-md-3">
                    <label class="col-form-label" for="numeroCliente">Numero</label>
                    <input type="text" class="form-control" id="numeroCliente" name="numeroCliente" value="<?php echo $cliente['numero']?>">
                </fieldset>
                <fieldset class="col-md-6">
                    <label class="col-form-label" for="telefoneCliente">Telefone</label>
                    <input type="text" class="form-control" id="telefoneCliente" name="telefoneCliente" value="<?php echo $cliente['telefone']?>">
                </fieldset>
            </div>
            <div class="form-group row">
                <fieldset class="col-md-7">
                    <label class="col-form-label" for="emailCliente">E-mail</label>
                    <input type="text" class="form-control" id="emailCliente" name="emailCliente" value="<?php echo $cliente['email']?>">
                </fieldset>
                <fieldset class="col-md-5">
                    <label class="col-form-label" for="cepCliente">CEP</label>
                    <input type="text" class="form-control" id="cepCliente" name="cepCliente" value="<?php echo $cliente['cep']?>">
                </fieldset>
            </div>
            <input type="submit" value="Editar Cadastro" class="btn btn-primary btn-lg btn-block btnCadastro">
        </form>
    </div>
</div>
