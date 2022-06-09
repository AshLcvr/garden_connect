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
})