$(document).ready(function () {

    console.log('coucou')

    const button_favory = $('#boutton_favory')
    button_favory.click( function (e) {
        e.preventDefault();
        button_favory.addClass('favory_active')
        console.log('ff')
    })
})