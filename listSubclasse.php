<?php
require "DAO/conection.php";

$sql = "SELECT * FROM subclasse";
$sql = $pdo->query($sql);

$subclasses = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Subclasse</h3>
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
        foreach ($subclasses as $sbc){
            echo "<tr onclick='ativaTable(this)'>";
            echo "<td>".$sbc['id']."</td>";
            echo "<td>".$sbc['nome']."</td>";
            echo "</tr>";
        }
    }


    ?>
    </tbody>
</table>
<a href="#!cadSubclasse" type="button" class="btn btn-outline-primary">Cadastrar</a>
