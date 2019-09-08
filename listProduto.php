<?php
require "DAO/conection.php";

$sql = "SELECT 
        P.id as id_produto,
        P.nome as nome_produto,
        P.cod_barra as barra,
        F.nome as nome_fornecedor,
        P.valor_compra as vlCompra,
        P.valor_venda as vlVenda      
        FROM produto P, fornecedor F
        WHERE P.id_fornecedor = F.id
        ORDER BY P.nome
        LIMIT 10";

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

if ($sql->rowCount()> 0){
    $produto = $sql->fetchAll();
}

$sql = "SELECT count(*) as qtd FROM produto";

try{
    $sql = $pdo->query($sql);

    $quantidadeProdutos = $sql->fetch();
}catch (PDOException $e){
    echo "Erro: ".$e->getMessage();
}

$quantidadePaginas = (float)$quantidadeProdutos['qtd']/10;

if ($quantidadePaginas > intval($quantidadePaginas)){
    $quantidadePaginas = intval($quantidadePaginas)+1;
}else{
    $quantidadePaginas = intval($quantidadePaginas);
}

?>

<head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="/resourse/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/resourse/css/style.css">
</head>
<h3 class="titulo">Cadastro de Produtos</h3>
<a href="#!cadProduto" type="button" class="btn btn-outline-primary">Cadastrar</a>
<div id="proximaTable"></div>
<table class="table table-hover" id="tabela">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nome</th>
        <th scope="col">Cod Barra</th>
        <th scope="col">Fornecedor</th>
        <th scope="col">Vl Compra</th>
        <th scope="col">Vl Venda</th>
    </tr>
    </thead>
    <tbody id="listItens">
    <?php
    foreach ($produto as $p){
        echo "<tr>";
        echo "    <td>".$p['id_produto']."</td>";
        echo "    <td>".$p['nome_produto']."</td>";
        echo "    <td>".$p['barra']."</td>";
        echo "    <td>".$p['nome_fornecedor']."</td>";
        echo "    <td>R$ ".$p['vlCompra']."</td>";
        echo "    <td>R$ ".$p['vlVenda']."</td>";
        echo "</tr>";
    }
    echo "</tbody>";
?>
</table>
    <div id="pagi">
    <ul class='pagination' id="pagiUl">
        <li class='page-item'>
            <a class='page-link' href="" onclick="paginaProduto(1,<?php echo $quantidadePaginas?>, 0)">&laquo;</a>
        </li>
        <li class='page-item active'>
            <a class='page-link' href="" onclick="paginaProduto(1, <?php echo $quantidadePaginas?>, 0)">1</a>
        </li>
        <?php
        if($quantidadePaginas>1):
        ?>
        <li class='page-item'>
            <a class='page-link' href="" onclick="paginaProduto(2, <?php echo $quantidadePaginas?>, 0)">2</a>
        </li>
        <?php
            endif;
        ?>
        <li class='page-item'>
            <a class='page-link' href="" onclick="paginaProduto(<?php echo $quantidadePaginas.", ".$quantidadePaginas?>, 0)">&raquo;</a>
        </li>
    </ul>
    </div>
    <div id="pagi2"></div>
