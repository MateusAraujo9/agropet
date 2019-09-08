var pagina;

function subMenu(sub) {
    var subMenu = document.getElementById(sub);

    if (subMenu.getAttribute("class")=="esconde" || subMenu.getAttribute("class") == null){
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
});

app.controller("meuAppCtrl", function ($scope, $http) {
    $scope.trocaPaginaCliente = function (pg) {
        $http.get('http://agropet.pc/setPaginaCliente.php?pagina='+pg);
        reload();
    }
})

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
    var input = document.getElementById(pagina);
    input.setAttribute("value", getPagina());

    document.getElementById("auto_enviar").submit();

}

function trocaSenha(id) {
    var elemento = document.getElementById("idUsuario");
    elemento.setAttribute("value", id);
}

function ativaTable(elemento) {
    if (elemento.getAttribute("class") == "table-active"){
        elemento.setAttribute("class", "");
    } else{
        elemento.setAttribute("class", "table-active");
    }

}

function pesquisaFornecedor() {
    var pForn = document.getElementById("fornecedor");

    //console.log("Vai pesquisar por: "+pFab.value);
    var fornecedor = [];
    $.get("DAO/consultaFornecedor.php", "pesquisa="+pForn.value, function( data ) {
        //console.log(data);

        var retorno = JSON.parse(data);
        //console.log(retorno);
        if (retorno.length == 0) {
            //alert("Nenhum fornecedor encontrado.");
            exibirAlerta(retorno.length);

            pForn.value = "";
        }else if(retorno.length == 1){
            pForn.value = retorno[0].nome;
        }else if (retorno.length > 1) {
            exibirListaFornecedores(retorno);
        }
    });
}

function fecharResultado() {
    var node = document.getElementById("tabela");
    var node1 = document.getElementById("pagi");
    
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