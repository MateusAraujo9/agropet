<?php
require "conection.php";

$texto = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$sql = "SELECT nome FROM produto WHERE nome like '".$texto."%' ORDER BY nome LIMIT 10";
$sql = $pdo->prepare($sql);
$sql->execute();

while($row = $sql->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row['nome'];
}

echo json_encode($data);