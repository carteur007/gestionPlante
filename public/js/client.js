/**
 * @author Saatsa franklin Blerio <saatsafranklin@gmail.com>
 * @description Analyste programmeur developpeur full-strack
 *
 */

/**
 * Constantes nessessaire a la manipulation dynamique du panier client
 *
 */
let contents = document.querySelectorAll('.contenu')
    ,dashboard_content = document.querySelector('.dashboard_content')
    ,tableau = document.querySelector('.dashboard_content_tableau')
    ,compte = document.querySelector('.dashboard_content_compte')
    ,produit = document.querySelector('.dashboard_content_produit')
    ,magasin = document.querySelector('.dashboard_content_magasin')
    ,promotion = document.querySelector('.dashboard_content_promotion')
    ,links = document.querySelectorAll(".linkm")
    ,payement = document.querySelector('.dashboard_content_payement');

let removeActiveLink = (liste) => {
    $.each(liste, function () {
        this.classList.remove('active');
    });
}
let renderPageContent = (content,contents)=>{
    hideDashboardContents(contents);
    content.style.display = 'block';
}

let hideDashboardContents = (liste) =>{
    liste.forEach((current)=>{
        current.style.display = 'none';
    });
}
//Gestion des evenements
$(document).ready(function () {
    links.item(0).classList.add('active');
    $(".hamburger .hamburger__inner").click(function () {
        $(".wrapper").toggleClass("active");
    });
    $(".top_navbar .fas").click(function () {
        $(".profile_dd").toggleClass("active");
    });
    $('.tooltipped').tooltip();
   
    links.forEach((current) => {
		current.addEventListener("click", (event) => {
            removeActiveLink(links);
            let i = parseInt(current.getAttribute('data-show'));
            if(i == 1){
                renderPageContent(tableau,contents);
            }
            if(i == 2){
                renderPageContent(compte,contents);
            }
            if(i == 3){
                renderPageContent(produit,contents);
            }
            if(i == 4){
                renderPageContent(magasin,contents);
            }
            if(i == 5){
                renderPageContent(promotion,contents);
            }
            if(i == 6){
                renderPageContent(payement,contents);
            }
        });
    });
});