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
                echo "<td><img src=\"resourse/imagens/cadeado.png\" alt=\"trocarSenha\" title=\"Trocar Senha\" class=\"btnListUser\" onclick=trocaSenha(".$user['id'].") data-toggle=\"modal\" data-target=\"#myModal\"></td>";
                echo "</tr>";
            }
        }


        ?>
    </tbody>
</table>
<a href="#!cadUsuario" type="button" class="btn btn-outline-primary">Cadastrar</a>


<!--Modal-->
<div class="modal" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trocar Senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="DAO/trocaSenhaUsuario.php" method="post">
                <div class="modal-body">
                    <input class="form-control" id="idUsuario" type="text" name="idUsuario" readonly="">
                    <!--<input type="text" disabled value="id" name="idUsuario" id="idUsuario" size="5"><br>-->
                    <label class="col-form-label" for="novaSenha">Nova Senha</label>
                    <input type="password" class="form-control" name="novaSenha" id="novaSenha">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Save changes">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>