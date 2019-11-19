<?php
require "DAO/conection.php";

$tkuser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"";

$sqlQuantCli = "SELECT count(*) as qtd FROM cliente";

try{
    $sqlQuantCli = $pdo->query($sqlQuantCli);

    $sqlQuantCli = $sqlQuantCli->fetch();
}catch (PDOException $e){
    echo $e->getMessage();
}
if ($sqlQuantCli['qtd'] > 0){


/*
 * Código para pegar página no banco de dados, baita gambi
 * */
$sql3 = "SELECT * FROM configuracoes C, usuario U
          WHERE C.usuario = U.id
          AND U.token = '$tkuser'
";

try{
    $sql3 = $pdo->query($sql3);

    $result = $sql3->fetch();
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$pag = $result['valor'];
/*
 * Pegou codigo
 * */



$primeiroItem = ($pag-1)*10;
$teste = 0;

$sql = "SELECT * FROM cliente ORDER BY nome LIMIT ".$primeiroItem. ", 10";

//Try para pegar clientes da página
try{
    $sql = $pdo->query($sql);

    $clientes = $sql->fetchAll();
}catch (PDOException $e){
    echo "Erro".$e->getMessage();
}

//Try para contar quantidade de páginas
$sql2 = "select COUNT(*) as C from cliente";
try{
    $sql2 = $pdo->query($sql2);

}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$vlDividido = (int)$sql2->fetch()['C'];

$vlDividido = $vlDividido/10;
$numPaginas = intval($vlDividido);
if ($numPaginas < ($vlDividido)){
    $numPaginas++;
}
}
?>
    <head>
        <!--Bootstrap-->
        <link rel="stylesheet" href="/resourse/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="/resourse/css/style.css">
    </head>
    <div id="compReact"></div>
    <h3 class="titulo">Cadastro de Clientes</h3>
    <a href="#!cadCliente" type="button" class="btn btn-outline-primary">Cadastrar</a>
    <table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">CPF</th>
        <th scope="col">Telefone</th>
        <th scope="col">Email</th>
        <th scope="col">Opções</th>
    </tr>
    </thead>
    <tbody>
<?php
if ($sqlQuantCli['qtd'] > 0){
    foreach ($clientes as $cl){
        echo "<tr onclick='ativaTable(this)'>";
        echo "    <td>".$cl['id']."</td>";
        echo "    <td>".$cl['nome']."</td>";
        echo "    <td>".$cl['cpf']."</td>";
        echo "    <td>".$cl['telefone']."</td>";
        echo "    <td>".$cl['email']."</td>";
        echo "    <td>";
        echo "      <img src=\"resourse/imagens/editar.png\" alt=\"editarUsuario\" title=\"Editar\" class=\"btnListUser\" onclick=\"window.location='editarCliente.php?cliente=".$cl['id']."'\">";
        echo "    </td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";


    echo "<div class='testando'>";
    echo "    <ul class='pagination'>";
    echo "        <li class='page-item'>";
    echo "            <a class='page-link' href='/#!/listCliente' ng-click='trocaPaginaCliente(1)'>&laquo;</a>";
    echo "        </li>";
    if (($pag-1)> 0){
        echo "        <li class='page-item'>";
        echo "            <a class='page-link' href='/#!/listCliente' ng-click='trocaPaginaCliente(".($pag-1).")'>".($pag-1)."</a>";
        echo "        </li>";
    }
    echo "        <li class='page-item active'>";
    echo "            <a class='page-link' href='/#!/listCliente' ng-click='trocaPaginaCliente(".$pag.")'>$pag</a>";
    echo "        </li>";
    if (($pag+1)<=$numPaginas){
        echo "        <li class='page-item'>";
        echo "            <a class='page-link' href='/#!/listCliente' ng-click='trocaPaginaCliente(".($pag+1).")'>".($pag+1)."</a>";
        echo "        </li>";
    }
    echo "        <li class='page-item'>";
    echo "            <a class='page-link' href='/#!/listCliente' ng-click='trocaPaginaCliente(".$numPaginas.")'>&raquo;</a>";
    echo "        </li>";
    echo "    </ul>";
    echo "</div>";
}


?>
