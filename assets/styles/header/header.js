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

// Menu dropdown connecté
let link_dropdown = document.getElementById("link_menu_drop");
let dropdown = document.getElementById("button_dropdown");
let ul_drop = document.getElementById("ul_dropdown");

if (link_dropdown != null) {
  link_dropdown.addEventListener("click", function (e) {
    e.preventDefault();
    dropdown.classList.toggle("open_drop");
    ul_drop.classList.toggle("open_drop");
  });
}

// Affichage du header quand scroll up
var lastScrollTop = 0;
let header = document.getElementById("header");
document.addEventListener(
    "scroll",
    function () {
      var st = window.pageYOffset || document.documentElement.scrollTop;
      if (st > lastScrollTop ) {
        header.classList.remove("fixed");
        header.classList.add("hidden");
      } else {
        header.classList.add("fixed");
        header.classList.remove("hidden");
      }
      lastScrollTop = st <= 0 ? 0 : st;
      if (st === 0) {
        header.classList.remove("fixed");
      }
    },
    false
);