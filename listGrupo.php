<?php
    require "DAO/conection.php";

    $sql = "SELECT * FROM grupo";
    $sql = $pdo->query($sql);

    $grupos = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Grupo</h3>
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
        foreach ($grupos as $gr){
            echo "<tr onclick='ativaTable(this)'>";
            echo "<td>".$gr['id']."</td>";
            echo "<td>".$gr['nome']."</td>";
            echo "</tr>";
        }
    }


    ?>
    </tbody>
</table>
<a href="#!cadGrupo" type="button" class="btn btn-outline-primary">Cadastrar</a>
