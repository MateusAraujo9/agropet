<?php
require "conection.php";

$listaProdutos = isset($_POST['lista'])?$_POST['lista']:"";
$tipoVenda = isset($_POST['tipo'])?$_POST['tipo']:"";
$subtotal = isset($_POST['subtotal'])?$_POST['subtotal']:"";