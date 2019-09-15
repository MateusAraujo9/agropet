<?php
require "conection.php";

$listaP = isset($_POST['lista'])?$_POST['lista']:"";
$fornecedor = isset($_POST['fornecedor'])?$_POST['fornecedor']:"";
$dataEntrada = isset($_POST['dataEntrada'])?$_POST['dataEntrada']:"";

//Variavel para controle de erro;
$erro = 0;

//Pegar fornecedor
$sqlF = "SELECT * FROM fornecedor WHERE nome = '$fornecedor'";

try{
    $sqlF = $pdo->query($sqlF);

    $sqlF = $sqlF->fetch();
}catch (PDOException $e){
    $erro = 1;
}

//Insert nota
$sqlNota = "INSERT INTO entrada_nota (id_fornecedor, data_entrada) VALUES ('".$sqlF['id']."', '$dataEntrada')";

try{
    $pdo->query($sqlNota);
}catch (PDOException $e){
    $erro = 1;
}

//Pegar id nota inserida
$sqlNotaInserida = "SELECT max(id) as id FROM entrada_nota";

try{
    $sqlNotaInserida = $pdo->query($sqlNotaInserida);

    $sqlNotaInserida = $sqlNotaInserida->fetch();
}catch (PDOException $e){
    $erro = 1;
}

//Inserindo itens da nota
foreach ($listaP as $p){
    $sql = "INSERT INTO entrada_item (id_produto, quantidade, custo_unitario, custo_total, id_nota) VALUES 
            ('".$p['idProduto']."', '".$p['quantidade']."', '".$p['custoU']."', '".$p['custoT']."', '".$sqlNotaInserida['id']."')";

    try{
        $pdo->query($sql);
    }catch (PDOException $e){
        $erro=1;
    }
}

//Atualizando valor de compra
foreach ($listaP as $p){
    $sql = "UPDATE produto set valor_compra = '".$p['custoU']."' WHERE id = '".$p['idProduto']."'";

    try{
        $pdo->query($sql);
    }catch (PDOException $e){
        $erro =1;
    }
}

if ($erro == 0){
    echo "sucesso";
}else{
    echo "erro";
}