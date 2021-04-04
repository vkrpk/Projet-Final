const menuDeroulant = document.getElementById('navbar-burger');
const iconeMenu = document.getElementsByClassName('icone-menu');

function afficherMenuDeroulant(){
    if(menuDeroulant.classList.contains('nav-ouverte')){
        menuDeroulant.classList.remove('nav-ouverte');
    } else{
        menuDeroulant.classList.add('nav-ouverte');
    }
}

for(let i = 0; i < iconeMenu.length; i++){
    iconeMenu[i].addEventListener("click", afficherMenuDeroulant);
}
