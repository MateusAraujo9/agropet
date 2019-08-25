<?php
require "DAO/conection.php";

$sql = "SELECT * FROM classe";
$sql = $pdo->query($sql);

$classes = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Classe</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($sql->rowCount() > 0){
        foreach ($classes as $cl){
            echo "<tr onclick='ativaTable(this)'>";
            echo "<td>".$cl['id']."</td>";
            echo "<td>".$cl['nome']."</td>";
            echo "</tr>";
        }
    }


    ?>
    </tbody>
</table>
<a href="#!cadClasse" type="button" class="btn btn-outline-primary">Cadastrar</a>
