$(document).ready(function () {
    $('#slider').flexslider({
        controlNav: false,
        directionNav: true,
    });

    $('#slider-category').flexslider({
        controlNav: false,
        directionNav: true,
        animation: "slide",
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 15,
        minItems: 2,
        maxItems: 4,
    });

    $('.slider-annonce').flexslider({
        controlNav: false,
        directionNav: false,
        animation: "slide",
        animationLoop: true,
    });

    $('.slider-boutique').flexslider({
        controlNav: true,
        directionNav: true,
        animation: "slide",
        animationLoop: true,
    });
})