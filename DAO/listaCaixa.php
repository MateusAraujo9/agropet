<?php
require "conection.php";

$dtIni = isset($_GET['dtIni'])?$_GET['dtIni']:"";
$dtFim = isset($_GET['dtFim'])?$_GET['dtFim']:"";

$sql = "SELECT 
        id,
        DATE_FORMAT(data_abertura, '%d/%m/%Y %H:%i:%S') AS data_abertura
        FROM caixa WHERE data_abertura BETWEEN '$dtIni' AND '$dtFim' ORDER BY id DESC";

try{
    $sql = $pdo->query($sql);
}catch (PDOException $e){
    $e->getMessage();
}

if ($sql->rowCount() > 0){
    $result = $sql->fetchAll();
    print_r(json_encode($result));
}


?>