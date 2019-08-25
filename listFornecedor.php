<?php
require "DAO/conection.php";

$sql = "SELECT * FROM fornecedor";
$sql = $pdo->query($sql);

$fornecedores = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Fornecedor</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">Razao</th>
        <th scope="col">Cnpj</th>
        <th scope="col">Telefone</th>
        <th scope="col">Email</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($sql->rowCount() > 0){
        foreach ($fornecedores as $forn){
            echo "<tr onclick='ativaTable(this)'>";
            echo "<td>".$forn['id']."</td>";
            echo "<td>".$forn['nome']."</td>";
            echo "<td>".$forn['razao']."</td>";
            echo "<td>".$forn['cnpj']."</td>";
            echo "<td>".$forn['telefone']."</td>";
            echo "<td>".$forn['email']."</td>";
            echo "</tr>";
        }
    }


    ?>
    </tbody>
</table>
<a href="#!cadFornecedor" type="button" class="btn btn-outline-primary">Cadastrar</a>
