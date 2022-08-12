// css général
import './styles/app.css';
import "./styles/buttons/buttons.css";
// css, js formulaire
import "./styles/form/form.css";
import "./styles/form/form.js";
// css, js header footer
import "./styles/header/header.css";
import "./styles/header/header.js";
import "./styles/footer/footer.css";
// css, js homepage
import "./styles/homepage/homepage.css";
// css, js annonce recherche / listing
import "./styles/annonce/annonce.css";
import "./styles/filter_annonce/filter.css";
import "./styles/filter_annonce/filter.js";
// css, boutique publique
import "./styles/boutique_public/boutique_public.css";
import "./styles/boutique_public/rating.js";
import "./styles/annonce_public/annonce_public.css";
// css, js profil
import "./styles/profil/profil.css";
import "./styles/notification/notification.css";


// Affichage du header quand scroll up sur front
var lastScrollTop = 0;
let header = document.getElementById("header");
document.addEventListener(
  "scroll",
  function () {
    var st = window.pageYOffset || document.documentElement.scrollTop;
    if (st > lastScrollTop || st == 0) {
      header.classList.remove("fixed");
    } else {
      header.classList.add("fixed");
    }
    lastScrollTop = st <= 0 ? 0 : st;
    // if (st == 0) {
    //   console.log("ok");
    // }
  },
  false
);