$(document).ready(function () {

  // Menu burger
  let link_burger = document.getElementById("link_burger");
  let burger      = document.getElementById("burger");
  let ul          = document.getElementById("ul_burger");

  link_burger.addEventListener("click", function (e) {
    e.preventDefault();
    burger.classList.toggle("open");
    ul.classList.toggle("open");
  });

  // Menu dropdown quand connecté
  let link_dropdown = document.getElementById("link_menu_drop");
  let dropdown      = document.getElementById("button_dropdown");
  let ul_drop       = document.getElementById("ul_dropdown");

  if (link_dropdown != null) {
    link_dropdown.addEventListener("click", function (e) {
      e.preventDefault();
      dropdown.classList.toggle("open_drop");
      ul_drop.classList.toggle("open_drop");
    });
  }

  // Modale
  $("#modal_button").click(function () {
    $("#myModal").css("display","block");
  });

  $("#isSessionActive").click(function () {
    sessionStorage.setItem("session", "en cours");
  });
  if (sessionStorage.getItem("session") === "en cours") {
      $("#myModal").hide();
  } else {
      $("#myModal").show();
  }

});
