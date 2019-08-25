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
});

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