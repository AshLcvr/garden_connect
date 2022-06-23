// Menu burger
/* Sélection des éléments HTML */
let link_burger = document.getElementById("link_burger");
let burger = document.getElementById("burger");
let ul = document.getElementById("ul_burger");

/* gestionnaire d'événement sur le a#link pour venir changer l'attribution de la classe .open à la ul et au span#burger */
link_burger.addEventListener("click", function (e) {
  e.preventDefault();
  burger.classList.toggle("open");
  ul.classList.toggle("open");
});
