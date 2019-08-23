function subMenu(sub) {
    var subMenu = document.getElementById(sub);

    if (subMenu.getAttribute("class")=="esconde" || subMenu.getAttribute("class") == null){
        subMenu.setAttribute("class", "");
    } else{
        subMenu.setAttribute("class", "esconde");
    }
}