import nouislider from "nouislider";
import 'nouislider/dist/nouislider.min.css';
import Filter from '../../modules/Filter';

// new Filter(document.querySelector('.js-filter'))

$(document).on('change', '.annonce_category', function () {

    //console.log('dede')
    let $field = $(this)
    let $form = $field.closest('form')
    let data = {}
    data[$field.attr('name')] = $field.val()
    console.log($form)
    $.ajax({
        url : $form.attr('action'),
        type: $form.attr('method'),
        data : data,
        complete: function(html) {
            console.log(html.responseText) // Je ne récupere pas la meme chose sur edit et sur new -> l'input disparait sur edit -> Erreur 500?
            $('.annonce_subcategory').replaceWith(
                $(html.responseText).find('.annonce_subcategory')
            );
        }
    });
})

var slider = document.getElementById('price-slider');

if (slider){
    const min = document.getElementById('min');
    const max = document.getElementById('max');
    const range = nouislider.create(slider, {
        start: [min.value || parseInt(slider.dataset.min) ,max.value || parseInt(slider.dataset.max)],
        connect: true,
        range: {
            'min': parseInt(slider.dataset.min, 10),
            'max': parseInt(slider.dataset.max, 10)
        }
    });

    range.on('slide', function (values,handle) {
            if(handle === 0){
                min.value = Math.round(values[0])
            }
            if(handle === 1){
                max.value = Math.round(values[1])
            }
    })
}
