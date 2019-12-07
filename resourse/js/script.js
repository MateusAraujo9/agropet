var pagina;
const listaProdutos = [];
var produtoC = {};
var listaPCaixa =[];
var subtotalCaixa = 0;
var statusCaixa = false;
var controleCaixa = 0;

function subMenu(sub) {
    let subMenu = document.getElementById(sub);

    if (subMenu.getAttribute("class")==="esconde" || subMenu.getAttribute("class") == null){
        subMenu.setAttribute("class", "");
    } else{
        subMenu.setAttribute("class", "esconde");
    }
}

var app = angular.module("meuApp", ["ngRoute"]);
app.config(function ($routeProvider) {
    $routeProvider
        .when("/", {templateUrl:"home.php"})
        .when("/listUsuario", {templateUrl: "listUsuario.php"})
        .when("/cadUsuario", {templateUrl: "cadUsuario.html"})
        .when("/listGrupo", {templateUrl: "listGrupo.php"})
        .when("/cadGrupo", {templateUrl: "cadGrupo.html"})
        .when("/listClasse", {templateUrl: "listClasse.php"})
        .when("/cadClasse", {templateUrl: "cadClasse.html"})
        .when("/listSubclasse", {templateUrl: "listSubclasse.php"})
        .when("/cadSubclasse", {templateUrl: "cadSubclasse.html"})
        .when("/listFornecedor", {templateUrl: "listFornecedor.php"})
        .when("/cadFornecedor", {templateUrl: "cadFornecedor.html"})
        .when("/setPaginaCliente/", {templateUrl: "setPaginaCliente.php"})
        .when("/listCliente/", {templateUrl: "listCliente.php"})
        .when("/cadCliente", {templateUrl: "cadCliente.html"})
        .when("/listProduto/", {templateUrl: "listProduto.php"})
        .when("/cadProduto", {templateUrl: "cadProduto.php"})
        .when("/ajustePreco", {templateUrl: "ajustePrecoProduto.html"})
        .when("/convertEstoque", {templateUrl: "conversaoDeEstoque.html"})
        .when("/entradaEstoque", {templateUrl: "entradaEstoque.html"})
        .when("/rVendas", {templateUrl: "relatorioVendas.html"})
        .when("/rCrediario", {templateUrl: "relatorioCrediario.html"})
        .when("/rCaixa", {templateUrl: "relatorioCaixa.html"})
});

app.controller("meuAppCtrl", function ($scope, $http) {
    $scope.trocaPaginaCliente = function (pg) {
        $http.get('http://agropet.pc/setPaginaCliente.php?pagina='+pg);
        reload();
    }
});

function reload() {
    window.location.reload();
}

function trocaPagina(elemento) {
    pagina = elemento.getAttribute("name");
    console.log(pagina);
}

function getPagina() {
    return pagina;
}
function setPagina(numero) {
    pagina = numero;
}
function definirPagina() {
    let input = document.getElementById(pagina);
    input.setAttribute("value", getPagina());

    document.getElementById("auto_enviar").submit();

}

function trocaSenha(id) {
    let elemento = document.getElementById("idUsuario");
    elemento.setAttribute("value", id);
}

function ativaTable(elemento) {
    if (elemento.getAttribute("class") === "table-active"){
        elemento.setAttribute("class", "");
    } else{
        elemento.setAttribute("class", "table-active");
    }

}

function pesquisaFornecedor() {
    let pForn = document.getElementById("fornecedor");


    $.get("DAO/consultaFornecedor.php", "pesquisa="+pForn.value, function( data ) {
        //console.log(data);

        let retorno = JSON.parse(data);
        //console.log(retorno);
        if (retorno.length === 0) {
            //alert("Nenhum fornecedor encontrado.");
            exibirAlerta(retorno.length, "Fornecedor");

            pForn.value = "";
        }else if(retorno.length === 1){
            pForn.value = retorno[0].nome;
        }else if (retorno.length > 1) {
            exibirListaFornecedores(retorno);
        }
    });
}

function fecharResultado() {
    let node = document.getElementById("tabela");
    let node1 = document.getElementById("pagi");
    
    if (node.parentNode) {
        node.parentNode.removeChild(node);
    }

    if (node1.parentNode) {
        node1.parentNode.removeChild(node1);
    }
}

function paginaProduto(pagina, ultimaPagina, remove) {
    if (remove === 0) {
        fecharResultado();
    }else{
       removerComponentesPagination();
    }

    if (pagina == null){
        console.log("sem pagina");
    } else{
        $.get("DAO/paginaProduto.php", "pagina="+pagina, function (data) {

            let retorno = JSON.parse(data);

            if (retorno.length > 0){
                mudarPaginaProduto(retorno, pagina, ultimaPagina);
            }
        })
    }
}

function ajustePreco(id) {
    let valor;
    let pesquisa = document.getElementById("produto");
    if (id === 0){
        valor = pesquisa.value;
    }else{
        valor = id;
    }
    if (valor == null) {
        console.log("Nenhum produto informado");
    }else{
        $.get("DAO/consultaProduto.php", "pesquisa="+valor, function (data) {
            let retorno = JSON.parse(data);

            if (retorno.length === 0){
                exibirAlerta(retorno.length, "Produto");

                pesquisa.value = "";
            }else if (retorno.length === 1) {
                //React para mostrar campos de alteração de valor
                let p = document.getElementById("idProduto");
                p.value = retorno[0].id;

                pesquisa.value = retorno[0].nome;
                document.getElementById("btnAjuste").disabled = false;
                fecharCompR();
                ajustePrecoR(retorno);
            }else{
                //React para mostrar tela para escolher produto, e depois de escolher vai mostrar campos de alteração de valor
                exibirListaProdutos(retorno, "produtoAjuste", "x");
            }
        })
    }
}

function pesquisaProdutoConvert(id, local) {
    let valor;
    if (id===0){
        if (local === "origem"){
            let p = document.getElementById("produtoOrigem");
            valor = p.value;
        } else{
            let p = document.getElementById("produtoDestino");
            valor = p.value;
        }
    }else{
        valor = id;
    }

    $.get("DAO/consultaProduto.php", "pesquisa="+valor, function (data) {
        let retorno = JSON.parse(data);

        if (retorno.length === 0){
            exibirAlerta(retorno.length, "Produto");

            if (local === "origem") {
                document.getElementById("produtoOrigem").value = "";
            }else{
                document.getElementById("produtoDestino").value = "";
            }

        }else if (retorno.length === 1) {
            if (local === "origem") {
                document.getElementById("produtoOrigem").value = retorno[0].nome;
                document.getElementById("idProdutoOrigem").value = retorno[0].id;

            }else{
                document.getElementById("produtoDestino").value = retorno[0].nome;
                document.getElementById("idProdutoDestino").value = retorno[0].id;
            }
        }else if (retorno.length > 1) {
            exibirListaProdutos(retorno, "produtoConvert", local);
        }

    })


}

function calculaEstoqueDestino() {
    let estoqueO = document.getElementById("estoqueOrigem");
    let ftConvert = document.getElementById("fator");
    let estoqueD = document.getElementById("estoqueDestino");

    let vlEstO = estoqueO.value.toString().replace(",", ".");
    let vlFator = ftConvert.value.toString().replace(",", ".");

    estoqueD.value = vlEstO*vlFator;

}


function entradaProduto(id) {
    let elPesquisa = document.getElementById("produto");
    let vlPesquisa = "";
    if (id !== 0) {
        vlPesquisa = id;
    }else{
        vlPesquisa = elPesquisa.value;
    }

    $.get("DAO/consultaProduto.php", "pesquisa="+vlPesquisa, function (data) {
        let retorno = JSON.parse(data);

        if (retorno.length === 0){
            exibirAlerta(retorno.length, "Produto");
        }else if (retorno.length === 1){
            let idProduto = document.getElementById("idProduto");
            let elUnidade = document.getElementById("unidade");
            let vlComp = document.getElementById("vlComp");

            vlComp.value = retorno[0].valor_compra;
            elPesquisa.value = retorno[0].nome;
            elUnidade.value = retorno[0].sigla;
            idProduto.value = retorno[0].id;
        }else if (retorno.length > 1) {
            exibirListaProdutos(retorno, "produtoEntrada", "x");
        }
    })
}

function addProdutoEntrada() {
   //elementos
    let elIdProduto = document.getElementById("idProduto");
    let elNomeProduto = document.getElementById("produto");
    let elCusto = document.getElementById("vlComp");
    let elQuantidade = document.getElementById("quantidade");

    //valores
    let idProduto = elIdProduto.value;
    let nomeProduto = elNomeProduto.value;
    let custoU = parseFloat(elCusto.value); //custo unitário
    let quantidade = parseFloat(elQuantidade.value);
    let custoT = custoU*quantidade;

    //impedir que adicione produto sem informação
    if(nomeProduto==="" || custoU ==="" || quantidade===""){
        alert("Preencha todos os campos do produto (nome, custo e quantidade)");
        return 0;
    }else if(idProduto===""){
        alert("Você deve pesquisar o produto clicando na lupa");
        return 0;
    }
    //Criar objeto
    let objProduto = new Object();

    objProduto.idProduto = idProduto;
    objProduto.nome = nomeProduto;
    objProduto.custoU = custoU;
    objProduto.custoT = custoT;
    objProduto.quantidade = quantidade;

    listaProdutos.push(objProduto);

    TabelaProdutosEntrada(listaProdutos);

    limpaProdutoEntrada();
}

function limpaProdutoEntrada() {
    document.getElementById("idProduto").value = "";
    document.getElementById("produto").value = "";
    document.getElementById("vlComp").value = "";
    document.getElementById("quantidade").value = "";
    document.getElementById("unidade").value = "";
}

function realizarEntrada() {
    let fornecedor = document.getElementById("fornecedor").value;
    let dataEntrada = document.getElementById("dataEntrada").value;

    if (fornecedor === "" || dataEntrada==="" || listaProdutos.length===0){
        alert("Campos obrigatórios não informados");
    }else{
        $.post("DAO/entradaEstoque.php", {lista:listaProdutos, fornecedor:fornecedor, dataEntrada:dataEntrada}, function (data) {
            let retorno = data.toString();
            if (retorno === "sucesso") {
                alert("Entrada realizada com sucesso");

                listaProdutos.pop();
                location.reload();
            }else{
                alert("Entrada não realizada, verifique as informações.");
            }
        })
    }
}

function pesquisaProdutoCaixa(pesquisa){
    $.get("DAO/consultaProduto.php", "pesquisa="+pesquisa, function (data) {
       let retorno = JSON.parse(data);

       if (retorno.length === 0) {
           exibirAlerta(retorno.length, "Produto");

       }else if (retorno.length===1){
            $('#produto')[0].value = retorno[0].nome;
            produtoC.id = retorno[0].id;
            produtoC.nome = retorno[0].nome;
            produtoC.vlBruto = parseFloat(retorno[0].valor_venda);
            produtoC.vlLiquido = retorno[0].valor_venda;

            $('#vlUnit')[0].value = retorno[0].valor_venda;


           $('#quantidade').focus();
           fecharCompReact();
       } else if (retorno.length > 1) {
            listaProdutosCaixa(retorno);

       }
    });
}

function apagarCamposCaixa(){
    $('#produto')[0].value = "";
    $('#quantidade')[0].value = "";
    $('#vlUnit')[0].value = "";
    $('#desconto')[0].value = "";
}

function acoesCaixa(opcao){
    if(opcao.value == 1){
        abreCaixa();
    }else if(opcao.value == 2){
       if (listaPCaixa.length > 0){
           alert("Não pode fechar o caixa com venda em andamento!")
       } else{
           fechaCaixa();
           $('#selectCaixa')[0].value = "";
       }
    }else if(opcao.value = 3){
        finalizarVenda("C");
    }
}

function abrirCaixaUser(cedula, moeda){
    $.get("DAO/abreCaixa.php", {cedula:cedula, moeda:moeda}, function (data) {
        let retorno = data;

        if (retorno === "erro") {
            alert("Não foi possivel abrir caixa");
        }else if (retorno === "Caixa Aberto") {
            alert("O caixa já está aberto");
        }else{
            retorno = JSON.parse(data);
            console.log(retorno);
            preencheFooterCaixa(retorno);
        }
    });

    $('#selectCaixa')[0].value = 0;
}

function consultaCaixaAberto(){
    $.ajax({
        url: "DAO/consultaCaixaAberto.php",
        async:false
    }).done(function (data) {
       if (data === "true"){
           statusCaixa = true;
       }else{
           statusCaixa = false;
       }

    })
}

function fechaCaixa(){
    $.ajax({
        url: "DAO/fecharCaixa.php",
        async:false
    }).done(function (data) {
        if (data === "true"){
            removerComponentesCaixa();
        }else{
            alert("Caixa já está Fechado!");
        }

    })
}

function limparFooterRodapeCaixa(){
    let node = document.getElementById("pFooterCaixa");
    if (node !== null){
        if (node.parentNode){
            node.parentNode.removeChild(node);
        }
    }
}

function finalizarVenda(tipoVenda){
    let elCli = "";
    let especValue = "";
    let dataVencimento = "";

    if (document.getElementById("cliente") === null) {
        elCli = "";
        especValue = "dinheiro";
    }else{
        elCli = document.getElementById("cliente").value;
        especValue = $("input[name='especie']:checked").val();
        if (especValue === "crediario"){
            dataVencimento = document.getElementById("vencimento").value;
        }
    }

    if (listaPCaixa.length === 0 || subtotalCaixa === 0){
        alert("Nenhuma venda para cancelar");
        $('#selectCaixa')[0].value = "";
    } else{
        if ((elCli === "" || elCli === null) && especValue === "crediario"){
            exibirAlertaModal("Cliente", "Para venda em crediário é necessário selecionar cliente");
            return false;
        }else if (especValue === undefined){
            exibirAlertaModal("Especie", "Para continuar selecione uma especie");
            return false;
        }else{
            $.post("DAO/finalizarVenda.php", {tipo:tipoVenda, cliente:elCli, subtotal:subtotalCaixa,
                especie:especValue, lista:listaPCaixa, vencimento:dataVencimento}, function (data) {
                if (data === "ok"){
                    limpaTelaCaixa(tipoVenda);
                    if (tipoVenda === "C"){
                        alert("Venda Cancelada");
                        $('#selectCaixa')[0].value = "";
                    }
                } else{
                    if (tipoVenda === "V"){
                        exibirAlertaModal("venda", "Venda não foi concluida, cancele e tente novamente");
                    }
                }
            })
        }
    }
}

function pesquisaCliente(id){
    let pCli = document.getElementById("cliente");
    let pesquisa = 0;

    if (id === 0) {
        pesquisa=pCli.value;
    }else{
        pesquisa=id;
    }
    $.get("DAO/consultaCliente.php", "pesquisa="+pesquisa, function (data) {
        let retorno = JSON.parse(data);

        if(retorno.length===0){
            exibirAlerta(retorno.length, "Cliente");
        }else if(retorno.length===1){
            let cliente = document.getElementById("cliente");

            cliente.value = retorno[0].nome;
        }else if (retorno.length > 1) {
            exibirListaClientes(retorno);
        }
    })

}

function limpaTelaCaixa(tipo){
    if (tipo === "V"){
        fecharPesquisaReact();
        fecharCompReact();
    }
    listaPCaixa = [];
    subtotalCaixa = 0;
    removerComponentR();
    apagarCamposCaixa();
    $('#subtotal')[0].value = "";
    $('#produto').focus();
}

//Remover aqui e na listagem do cadastro de cliente
function receberCrediario(id){
    if (id === null){
        alert("Não foi informado cliente");
    }else{
        $.get("DAO/consultaCliente.php", "pesquisa="+id, function (data) {
            let retorno = JSON.parse(data);

            exibirModalCrediario(retorno);
        })
    }
}

function baixarCrediario(idCliente, idCrediario){
    let especie = $("input[name='especie']:checked").val();
    if (especie === undefined) {
        exibirAlertaModal("Especie", "Para continuar selecione uma especie");
    }else{
        $.post("DAO/baixarCrediario.php", {idCliente:idCliente, idCrediario:idCrediario, especie:especie}, function (data) {

            if (data === ""){
                alert("Crediário recebido");
                fecharCompR();
                window.reload();
            } else{
                alert("Erro inesperado");
                console.log(data);
            }
        })
    }
}

function consultarVendas(pagina){
    let dtIni = $('#dtIni')[0].value;
    let dtFim = $('#dtFim')[0].value;
    let soCliente = document.getElementById("soCliente").checked;
    let porItem = document.getElementById("porItem").checked;
    let cliente = $('#cliente')[0].value;
    if (dtIni === "" || dtFim === ""){
        console.log("Deve ser informada data de inicio e fim");
    } else if(dtFim < dtIni){
        console.log("Data final não pode ser anterior a data inicial");
    }else{
        consultaVendasDao(dtIni, dtFim, soCliente, cliente, porItem, pagina);
    }
}

function consultaVendasDao(dtIni, dtFim, soCliente, cliente, porItem, pagina) {
    $.get("DAO/consultaVendas.php", {dtIni:dtIni, dtFim:dtFim, soCliente:soCliente, cliente:cliente, porItem:porItem, pagina:pagina}, function (data) {

        let retorno = "";
        if(isJson(data)){
            retorno = JSON.parse(data);
            removerFiltro();
            exebirRelatorioVendas("Relatório de Vendas", retorno, porItem, cliente, soCliente, dtIni, dtFim);
        }else{
            alert("Não retornou nenhuma venda.");
        }
    })
}

function removerFiltro(){
    let node = document.getElementById("titulo");
    let node1 = document.getElementById("filtro");

    if (node !== null) {
        if (node.parentNode) {
            node.parentNode.removeChild(node);
        }
    }

    if (node1 !== null) {
        if (node1.parentNode) {
            node1.parentNode.removeChild(node1);
        }
    }
}

function calcularQuantidade(elemento){
    let p = $('#produto')[0];
    if (p.value === ""){
        elemento.value = "";
        $('#quantidade')[0].value = "";
        p.focus();

    } else{
        let valor = parseFloat(elemento.value.replace(",", "."));
        let novaQuantidade = valor / produtoC.vlBruto;
        if (isNaN(novaQuantidade)){
            $('#quantidade')[0].value = "";
        }else{
            $('#quantidade')[0].value = (novaQuantidade.toFixed(3)+"").replace(".", ",");
        }

    }
}

function calcularValor(elemento){
    let p = $('#produto')[0];
    if (p.value === ""){
        elemento.value = "";
        $('#vlUnit')[0].value = "";
        p.focus();
    } else{
        let quant = parseFloat(elemento.value.replace(",", "."));
        //console.log(quant);
        let novoValor = quant* produtoC.vlBruto;
       if (isNaN(novoValor)){
           $('#vlUnit')[0].value = "";
       }else{
           $('#vlUnit')[0].value = (novoValor.toFixed(3)+"").replace(".", ",");
       }
    }

}

function consultarCrediario(pagina){
    let tipo = $('#tipo')[0].value;
    let dtIni = $('#dtIni')[0].value;
    let dtFim = $('#dtFim')[0].value;
    let cliente = $('#cliente')[0].value;

    //console.log("Tipo: "+tipo+"\nCliente: "+cliente+"\nInicio: "+dtIni+"\nFim: "+dtFim+"\nPagina: "+pagina);

    if (dtIni == "" || dtFim == "" || dtIni > dtFim) {
        alert("Erro, não vai dar não!");
    }else{
       consultaCrediarioDao(tipo, dtIni, dtFim, cliente, pagina);
    }
}

function consultaCrediarioDao(tipo, dtIni, dtFim, cliente, pagina){
    $.get("DAO/consultaCrediario.php", {tipo:tipo, dtIni:dtIni, dtFim:dtFim, cliente:cliente, pagina:pagina}, function (data) {
        if (data == "false") {
            alert("Nenhum crediário encontrador");
        }else{
            let retorno = JSON.parse(data);
            // //console.log(retorno[retorno.length - 2]);
            // for (let attr in retorno[retorno.length - 2]){
            //     console.log(attr+"\n");
            // }
            removerFiltro();
            exibirRelatorioCrediario("Relatório de Crediário", retorno, tipo, dtIni, dtFim, cliente);
        }
    })
}

function receberCrediarioUnico(id_cliente, id_crediario){

    if (id_cliente === null || id_crediario === null){
        alert("Recebimento não realizado")
    } else{
        $.get("DAO/consultaCrediarioCliente.php", {idCliente:id_cliente, idCrediario:id_crediario}, function(data){
            let retorno = JSON.parse(data);

            exibirModalCrediario(retorno);
        })
    }
}

function listarProdutosCrediario(idCrediario){
    $.get("DAO/produtosCrediario.php", {idCrediario:idCrediario}, function (data) {
        if(isJson(data)){
            let retorno = JSON.parse(data);
            exibirModalItensCrediario(retorno);
        }else if (data === "parcial") {
            alert("Crediário parcial");
        }
    })
}

function recebimentoParcial(cliente, dtIni, dtFim){
    let valor_pago = $('#vlPago')[0].value;
    valor_pago = valor_pago.toString().replace(".", "").replace(",", ".");
    let especie = $("input[name='especie']:checked").val();
    if (valor_pago === ""){
        exibirAlertaModal("especie", "Informe o valor para pagar");
    }else if (especie === undefined){
        exibirAlertaModal("especie", "Para continuar selecione uma espécie");
    }else{
        //vai enviar dados para backend
        console.log("Vai receber crediário");

        $.post("DAO/recebimentoParcial.php", {cliente:cliente, dtIni:dtIni, dtFim:dtFim, especie:especie, vlReceb:valor_pago}, function (data) {
            if (data === "") {
                alert("Valor recebido");
                fecharCompR();
                $('#menuReceb')[0].value = "";
            }
            reload();
        })
    }
    // alert(cliente+"\n"+dtIni+"\n"+dtFim+"\n"+valor_pago+"\n"+especie);
}

function buscarListaCaixa(){
    let dtIni = document.getElementById('dtIni').value;
    let dtFim = document.getElementById('dtFim').value;

    if (dtIni == "" || dtFim == ""){
        console.log("Não vai buscar lista");
    } else{
        $.get("DAO/listaCaixa.php", {dtIni:dtIni, dtFim:dtFim}, function (data) {
            if (data == ""){
                alert("Nenhum caixa no periodo");
            } else{
                let retorno = JSON.parse(data);
                exibirSelectCaixa(retorno);
            }

        })
    }
}

function buscaRelatorioCaixa(){
    let valorSelect = $("#selectCaixaList").val();
    if (valorSelect == -1 || valorSelect == undefined){
        alert("Selecione o Caixa!");
    }else{
        $.get("DAO/buscaRelatorioCaixa.php", {ncaixa:valorSelect}, function (data) {
            /*
            No array os retornos são os seguintes:
            0 = Informações do caixa (numero, data de abertura e fechamento, valor de abertura em cedula e moeda)
            1 = Valores por especie
            2 = Crediario recebido
            3 = Ticket médio
            4 = Quantidade de clientes atendidos
            5 = Valor total vendido
            6 = Valor de desconto
             */
            let retorno = JSON.parse(data);
            let container = $(".containerCenter")[0];
            container.className = "containerCenter2";
            console.log(retorno);
            removerFiltro();
            exibirRelatorioCaixa(retorno);
        })
    }
}

function listarProdutosVenda(idVenda){
    $.get("DAO/produtosVenda.php", {idVenda:idVenda}, function (data) {
        if(isJson(data)){
            let retorno = JSON.parse(data);
            exibirModalItensCrediario(retorno);
        }else if (data === "parcial") {
            alert("Crediário parcial");
        }
    })
}

function cadastrarCliente() {
    //Buscando dados
    let nome = $("#nome")[0].value;
    let cpf = $("#cpf")[0].value;
    let dtNasc = $("#dtNasc")[0].value;
    let estado = $("#estado")[0].value;
    let cidade = $("#cidade")[0].value;
    let bairro = $("#bairro")[0].value;
    let rua = $("#rua")[0].value;
    let numero = $("#numero")[0].value;
    let cep = $("#cep")[0].value;
    let tel = $("#tel")[0].value;
    let email = $("#email")[0].value;

    if (dtNasc === ""){
        dtNasc = "2000-01-01";
    }

    if (nome === ""){
        alert("Informe o nome do cliente");
    } else{
        $.post("DAO/cadCliente.php", {nome:nome, cpf:cpf, dtNasc:dtNasc, estado:estado, cidade:cidade, bairro:bairro, rua:rua, cep:cep, numero:numero, tel:tel, email:email}, function (data) {
            if (data === "erro nome"){
                alert("Nome inválido");
            } else if (data !== ""){
                alert("Erro inesperado, revise os campos do cadastro.");
            } else{
                alert("Cliente cadastrado com sucesso");
                window.location.href = "/#!/listCliente";
            }

        })
    }
}

function cadastrarProduto() {
    //Buscando dados
    let nome = $("#nome")[0].value;
    let barra = $("#barra")[0].value;
    let qtd = $("#qtd")[0].value;
    let unidade = $("#unidade")[0].value;
    let grupo = $("#grupo")[0].value;
    let classe = $("#classe")[0].value;
    let subCl = $("#subCl")[0].value;
    let fornecedor = $("#fornecedor")[0].value;
    let vlComp = $("#vlComp")[0].value;
    let vlVen = $("#vlVen")[0].value;


    if (nome === ""){
        alert("Informe o nome do produto");
    } else if (vlVen === ""){
        alert("Informe o valor de venda do produto");
    }else{
        $.post("DAO/cadProduto.php", {nome:nome, barra:barra, qtd:qtd, unidade:unidade, grupo:grupo, classe:classe, subCl:subCl, fornecedor:fornecedor, vlComp:vlComp, vlVen:vlVen}, function (data) {
            if (data !== ""){
                alert("Erro inesperado, verifique os campos do cadastro!");
                console.log(data);
            } else{
                alert("Produto cadastrado com sucesso!");
                window.location.href = "/#!/listProduto";
            }
        })
    }
}

function cadastrarGrupo() {
    let nome = $("#nome")[0].value;
    if (nome === ""){
        alert("Informe o nome.");
    }else{
        $.post("DAO/cadGrupo.php", {nome:nome}, function (data) {
            if (data !== ""){
                alert("Erro inesperado, revise o campo ou recarregue a página.");
            } else{
                alert("Grupo cadastrado com sucesso");
                window.location.href = "/#!/listGrupo";
                reload();
            }
        })
    }
}

function cadastrarClasse() {
    let nome = $("#nome")[0].value;
    if (nome === ""){
        alert("Informe o nome.");
    }else{
        $.post("DAO/cadClasse.php", {nome:nome}, function (data) {
            if (data !== ""){
                alert("Erro inesperado, revise o campo ou recarregue a página.");
            } else{
                alert("Classe cadastrada com sucesso");
                window.location.href = "/#!/listClasse";
                reload();
            }
        })
    }
}

function cadastrarSubClasse() {
    let nome = $("#nome")[0].value;
    if (nome === ""){
        alert("Informe o nome.");
    }else{
        $.post("DAO/cadSubclasse.php", {nome:nome}, function (data) {
            if (data !== ""){
                alert("Erro inesperado, revise o campo ou recarregue a página.");
            } else{
                alert("Subclasse cadastrada com sucesso");
                window.location.href = "/#!/listSubclasse";
                reload();
            }
        })
    }
}

function cadastrarFornecedor() {
    let nome = $("#nome")[0].value;
    let razao = $("#razao")[0].value;
    let cnpj = $("#cnpj")[0].value;
    let tel = $("#tel")[0].value;
    let email = $("#email")[0].value;

    if (nome === ""){
        alert("Informe o nome.");
    }else{
        $.post("DAO/cadFornecedor.php", {nome:nome, razao:razao, cnpj:cnpj, tel:tel, email:email}, function (data) {
            if (data !== ""){
                alert("Erro inesperado, revise os campos ou recarregue a página.");
            } else{
                alert("Fornecedor cadastrado com sucesso");
                window.location.href = "/#!/listFornecedor";
                reload();
            }
        })
    }
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }

    return true;
}


//Mascaras jquery
$('.valor').mask('#.##0,00', {reverse: true});
$('.quantidade').mask('#.##0', {reverse: true});
$('.quantidadeDec').mask('#.##0,000', {reverse: true});
$('.desconto').mask('00,00', {reverse: true});
$('.valorVenda').mask('#.##0,000', {reverse: true});
$('.cpf').mask('000.000.000-00');
$('.numero').mask('0000000');
$('.cep').mask('00000-000');
$('.cnpj').mask('00.000.000/0000-00');
$('.numeroCaixa').mask('##0,00', {reverse: true});

