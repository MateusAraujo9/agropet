var pagina;
const listaProdutos = [];
const produtoC = {};

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
        .when("/caixa", {templateUrl: "caixa.html"})
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
            produtoC.vlBruto = retorno[0].valor_venda;
            produtoC.vlLiquido = retorno[0].valor_venda;

            $('#vlUnit')[0].value = retorno[0].valor_venda;


           $('#quantidade').focus();
       } else if (retorno.length > 1) {


       }
    });
}

//Mascaras jquery
$('.valor').mask('#.##0,00', {reverse: true});
$('.quantidade').mask('#.##0', {reverse: true});
$('.quantidadeDec').mask('#.##0,000', {reverse: true});
$('.desconto').mask('00,00', {reverse: true});
$('.valorVenda').mask('#.##0,000', {reverse: true});