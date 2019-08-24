<?php
    require "DAO/conection.php";
    $sql = "SELECT * FROM usuario";
    $sql = $pdo->query($sql);

    $usuarios = $sql->fetchAll();
?>
<h3 class="titulo">Cadastro de Usuários</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">Opções</th>
    </tr>
    </thead>
    <tbody>
        <?php
        if ($sql->rowCount() > 0){
            foreach ($usuarios as $user){
                echo "<tr onclick='ativaTable(this)'>";
                echo "<td>".$user['id']."</td>";
                echo "<td>".$user['nome']."</td>";
                echo "<td><img src=\"resourse/imagens/cadeado.png\" alt=\"trocarSenha\" title=\"Trocar Senha\" class=\"btnListUser\" onclick=trocaSenha(".$user['id'].")></td>";
                echo "</tr>";
            }
        }


        ?>
    </tbody>
</table>
<a href="#!cadUsuario" type="button" class="btn btn-outline-primary">Cadastrar</a>
