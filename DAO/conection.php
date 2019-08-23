<?php
$dbsn = "mysql:dbname=agropet;host=localhost";
$dbuser = "root";
$dbpass = "";

try{
    $pdo = new PDO($dbsn, $dbuser, $dbpass);
}catch (PDOException $e){
    echo "Erro de conexão: ".$e->getMessage();
}

