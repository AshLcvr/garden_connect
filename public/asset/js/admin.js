$(document).ready(function () {
    $('#slider').flexslider({
        controlNav: false,
        directionNav: true,
    });
})

$(function(){
    $('.fa-minus').click(function(){
        $(this).closest('.chatbox').toggleClass('chatbox-min');
    });
    $('.fa-close').click(function(){
        $(this).closest('.chatbox').hide();
    });
});

function toggleMenu () {
    const navbar = document.querySelector('.navbar');
    const burger = document.querySelector('.burger');
    burger.addEventListener('click', (e) => {
        navbar.classList.toggle('show-nav');
    });
}
toggleMenu();
