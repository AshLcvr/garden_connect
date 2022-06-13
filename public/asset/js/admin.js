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