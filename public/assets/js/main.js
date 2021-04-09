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


const dMode = document.getElementsByClassName('DM');
const buttonDarkMode = document.getElementById('btn-darkmode');



function activerDarkMode() {
for(let i = 0; i < dMode.length; i++){
    dMode[i].classList.toggle("darkmode");}}
   
    buttonDarkMode.addEventListener("click", activerDarkMode);
