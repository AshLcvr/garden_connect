$(document).ready(function () {
    
    const aFocus = $('header nav ul li a');

    aFocus.on('click', function() {
        if (aFocus.hasClass('active')) {
            aFocus.removeClass('active');
        }else {
            aFocus.addClass('active');
        }
    })
});