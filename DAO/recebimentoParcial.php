<?php
require "conection.php";

$cliente = isset($_POST['cliente'])?$_POST['cliente']:"";
$dtIni = isset($_POST['dtIni'])?$_POST['dtIni']:"";
$dtFim = isset($_POST['dtFim'])?$_POST['dtFim']:"";
$especie = isset($_POST['especie'])?$_POST['especie']:"";
$valorRecebido = isset($_POST['vlReceb'])?$_POST['vlReceb']:"";
$valorRecebido = floatval($valorRecebido);


//Verifica se o valor recebido é menor que o total devido no periodo
$sqlBuscaCliente = "SELECT id FROM cliente WHERE nome = '$cliente'";
try{
    $sqlBuscaCliente = $pdo->query($sqlBuscaCliente);
}catch (PDOException $e){
    echo $e->getMessage();
}

if ($sqlBuscaCliente->rowCount() > 0){
    $idCliente = $sqlBuscaCliente->fetch()['id'];
}

$sqlVlDevido = "SELECT sum(valor_a_pagar) as total FROM crediario              
                WHERE id_cliente = '$idCliente'
                AND data_inclusao BETWEEN '$dtIni' AND '$dtFim'
                AND valor_pago is null 
                AND data_pagamento is null";

try{
    $sqlVlDevido = $pdo->query($sqlVlDevido);
}catch (PDOException $e){
    echo $e->getMessage();
}

if ($sqlVlDevido->rowCount() > 0){
    $vlDevido = $sqlVlDevido->fetch()['total'];
}

if ($valorRecebido > $vlDevido){
    //Valor recebido maior que valor devido no periodo, ERRO
    echo "valor maior";
}else{
    //Pegar id de caixa aberto
    $sqlCaixa = "SELECT id FROM caixa WHERE data_fechamento is null AND tipo = 'A'";
    try{
        $idCaixa = $pdo->query($sqlCaixa)->fetch()['id'];
    }catch (PDOException $e){
        echo "Erro na conexão: ".$e->getMessage();
    }

    //Caso o valor seja menor, vai buscar uma lista com as contas do periodo
    $listaCrediario = "SELECT id, valor_a_pagar FROM crediario              
                       WHERE id_cliente = '$idCliente'
                       AND data_inclusao BETWEEN '$dtIni' AND '$dtFim'
                       AND valor_pago is null 
                       AND data_pagamento is null
                       ORDER BY id";

    try{
        $listaCrediario = $pdo->query($listaCrediario);
    }catch (PDOException $e){
        echo $e->getMessage();
    }

    if ($listaCrediario->rowCount() > 0){
        $listaCrediario = $listaCrediario->fetchAll();
        $qtdCred = count($listaCrediario);
        $controlador = true;
        $count = 0;

        while ($controlador){
            $idCrediario = $listaCrediario[$count]['id'];
            $vlAPagar = $listaCrediario[$count]['valor_a_pagar'];

            if ($valorRecebido >= $vlAPagar){
                //codigo para pagamento total da conta, e retirar do saldo. looping continua caso o saldo recebido seja maior
                $sqlReceber = "UPDATE crediario SET valor_pago = valor_a_pagar, data_pagamento = now() WHERE id = $idCrediario";
                try{
                    $pdo->query($sqlReceber);
                }catch (PDOException $e){
                    echo $e->getMessage();
                }

                //Iserir na movimentação do caixa
                $sqlInsertMov = "INSERT INTO movimentacao_caixa (id_crediario, id_caixa, tipo, id_especie) 
                     VALUES ($idCrediario, $idCaixa, 'T', $especie)";

                try{
                    $pdo->query($sqlInsertMov);
                }catch (PDOException $e){
                    echo "Erro de conexão: ".$e->getMessage();
                }

                //update do valor comprado
                $sqlValorComprado = "UPDATE cliente SET valor_comprado = valor_comprado - $vlAPagar WHERE id = $idCliente";

                try{
                    $pdo->query($sqlValorComprado);
                }catch (PDOException $e){
                    $e->getMessage();
                }

                $valorRecebido -= $vlAPagar;

                if ($valorRecebido == $vlAPagar){
                    $controlador = false;
                }
            }else if ($valorRecebido < $vlAPagar){
                //Codigo para pagamento parcial, termina de zerar o saldo e encerra o looping
                $sqlReceber = "UPDATE crediario SET valor_pago = $valorRecebido, data_pagamento = now() WHERE id = $idCrediario";
                try{
                    $pdo->query($sqlReceber);
                }catch (PDOException $e){
                    echo $e->getMessage();
                }

                //Iserir na movimentação do caixa
                $sqlInsertMov = "INSERT INTO movimentacao_caixa (id_crediario, id_caixa, tipo, id_especie) 
                     VALUES ($idCrediario, $idCaixa, 'P', $especie)";

                try{
                    $pdo->query($sqlInsertMov);
                }catch (PDOException $e){
                    echo "Erro de conexão: ".$e->getMessage();
                }

                //Inserindo diferença de pagamento
                $valorDiferenca = $vlAPagar - $valorRecebido;

                $sqlInsertCrediario = "INSERT INTO crediario (id_cliente, valor_a_pagar, data_inclusao, data_vencimento) 
                                VALUES ($idCliente, '$valorDiferenca', now(), ADDDATE(CURDATE(), INTERVAL 30 DAY))";


                try{
                    $pdo->query($sqlInsertCrediario);
                }catch (PDOException $e1){
                    $e1->getMessage();
                }catch (Exception $e2){
                    echo $e2->getMessage();
                }

                //Update valor comprado
                $sqlValorComprado = "UPDATE cliente SET valor_comprado = valor_comprado - $valorRecebido WHERE id = $idCliente";

                try{
                    $pdo->query($sqlValorComprado);
                }catch (PDOException $e){
                    $e->getMessage();
                }


                $valorRecebido = 0;
                $controlador = false;
            }

            $count++;
            if ($count == $qtdCred){
                $controlador = false;
            }
        }
}

//Vai realizar um lopping debitando o valor de cada conta do valor pago
//Caso o saldo do valor pago seja menor que o valor da conta, vai pagar a conta parcialmente e gerar uma diferença de pagamento.
//Se o saldo for maior, simplesmente vai pagar a conta e debitar o valor da conta do saldo do valor pago
//Para cada crediário, deve inserir a movimentação no caixa, no caso do parcial, deve inserir tipo parcial


//Aqui vai gerar uma conta com a diferença de pagamento
}
