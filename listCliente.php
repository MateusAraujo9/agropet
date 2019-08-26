<?php
require "DAO/conection.php";

$sql = "SELECT * FROM cliente";
$sql = $pdo->query($sql);

$clientes = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Clientes</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">CPF</th>
        <th scope="col">Telefone</th>
        <th scope="col">Email</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($sql->rowCount() > 0){
        foreach ($clientes as $cl){
            echo "<tr onclick='ativaTable(this)'>";
            echo "<td>".$cl['id']."</td>";
            echo "<td>".$cl['nome']."</td>";
            echo "<td>".$cl['cpf']."</td>";
            echo "<td>".$cl['telefone']."</td>";
            echo "<td>".$cl['email']."</td>";
            echo "</tr>";
        }
    }


    ?>
    </tbody>
</table>
<a href="#!cadCliente" type="button" class="btn btn-outline-primary">Cadastrar</a>
