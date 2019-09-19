<?php
    require "DAO/conection.php";

    $tkUser = isset($_COOKIE['tkuser'])?$_COOKIE['tkuser']:"0";

    $sql = "SELECT C.id, U.nome FROM caixa C, usuario U WHERE C.id_usuario = U.id AND U.token = '$tkUser' AND C.tipo = 'A'";

    try{
        $sql = $pdo->query($sql);

        $result = $sql->fetch();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
?>

<script>
    $(document).keypress(function(e) {
        if (e.which === 13) {
            if (e.target.getAttribute("id") === "produto") {
                let caixa;
                $.when(consultaCaixaAberto()).done(caixa = statusCaixa);
                if (caixa){
                    let pesquisa = $('#produto')[0].value;
                    pesquisaProdutoCaixa(pesquisa);
                } else{
                    alert("Caixa Fechado");
                    console.log(caixa);
                }
            }else if(e.target.getAttribute("id") === "quantidade"){
                let quant = $('#quantidade')[0].value;
                if (quant !== "" && quant != null && quant > 0){
                    produtoC.quantidade = quant;
                    produtoC.vlTotal = produtoC.quantidade*produtoC.vlLiquido;
                    produtoC.vlTotal = parseFloat(produtoC.vlTotal.toFixed(3));
                    let elVlVen = $('#vlUnit')[0];
                    elVlVen.value = produtoC.vlTotal;
                    $('#desconto').focus();
                }
            }else if(e.target.getAttribute("id") === "desconto"){
                let caixa;
                $.when(consultaCaixaAberto()).done(caixa = statusCaixa);
                let p =  $('#produto');
                if (caixa) {
                        if (p[0].value !== "" && p[0].value !== null) {
                            let elDesconto = $('#desconto')[0];
                            let elVlVen = $('#vlUnit')[0];

                            if (elDesconto.value === "") {
                                elDesconto.value = 0;
                            }
                            //Salvando valor do desconto
                            let desconto = parseFloat(elDesconto.value.replace(",", "."));
                            produtoC.desconto = desconto;

                            let novoValor = produtoC.vlBruto - (produtoC.vlBruto * (desconto / 100));

                            produtoC.vlLiquido = parseFloat(novoValor.toFixed(3));
                            produtoC.vlTotal = parseFloat((produtoC.vlLiquido * produtoC.quantidade).toFixed(3));

                            elVlVen.value = parseFloat(produtoC.vlTotal);

                            listaPCaixa.push(produtoC);
                            subtotalCaixa = parseFloat((subtotalCaixa + produtoC.vlTotal).toFixed(3));
                            produtoC = {};
                            $('#subtotal')[0].value = subtotalCaixa;
                            p.focus();

                            atualizarListaCaixa(listaPCaixa);
                            apagarCamposCaixa();
                    }else{
                        alert("Informe o Produto!");
                        }
                }else{
                    alert("Caixa Fechado");
                }
            }
        }
    });
</script>
<div class="container telaCaixa">
    <div id="compReact"></div>
   <div class="titleCaixa">
        <h3 class="titulo">CAIXA</h3>
        <div class="form-group opcoesCaixa">
            <select class="custom-select" onchange="acoesCaixa(this)" id="selectCaixa">
                <option selected=""></option>
                <option value="1">Abrir Caixa</option>
                <option value="2">Fechar Caixa</option>
                <option value="3">Cancelar Venda</option>
            </select>
        </div>
   </div>
    <div class="form-group">
        <input class="form-control form-control-lg" type="text" placeholder="Id/CEAN/Nome Produto" id="produto">
    </div>
    <div class="corpoCaixa">
        <div class="componentR" id="componentR"></div>
        <div class="valoresCaixa">
            <div class="linhaCaixa">
                <div class="form-group camposCaixa">
                    <label class="col-form-label col-form-label-lg" for="quantidade">Quantidade</label>
                    <input class="form-control form-control-lg inputCaixaQtd quantidadeDec" type="text" id="quantidade" name="quantidade" maxlength="12">
                </div>
                <div class="form-group camposCaixa">
                    <label class="col-form-label col-form-label-lg" for="vlUnit">Valor Venda</label>
                    <input class="form-control form-control-lg valorVenda" type="text" id="vlUnit" name="vlUnit" readonly="">
                </div>
            </div>
            <div class="linhaCaixa">
                <div class="form-group camposCaixa">
                    <label class="col-form-label col-form-label-lg" for="desconto">Desconto (%)</label>
                    <input class="form-control form-control-lg desconto" type="text" id="desconto" name="desconto">
                </div>
                <div class="form-group camposCaixa">
                    <label class="col-form-label col-form-label-lg" for="subtotal">Subtotal</label>
                    <input class="form-control form-control-lg inputCaixaQtd" type="text" id="subtotal" name="subtotal" readonly="">
                </div>
            </div>
            <div class="linhaCaixa">
                <button class="btn btn-success btn-block botaoCaixa" onclick="abrirModalFinalizarVenda(listaPCaixa, 'V', subtotalCaixa)">FINALIZAR VENDA</button>
            </div>
        </div>
    </div>
    <div class="footer" id="footer">
        <?php
        if ($sql->rowCount() > 0){
            echo "<p id='pFooterCaixa'>CAIXA ABERTO - Nº Caixa: ".$result['id']." - Usuario: ".$result['nome']."</p>";
        }
        ?>
    </div>

</div>


