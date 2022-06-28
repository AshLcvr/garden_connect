// Ajax categories select
$(document).on('change', '.annonce_category', function () {
    let $field = $(this)
    let $form = $field.closest('form')
    let data = {}
    data[$field.attr('name')] = $field.val()
    $.ajax({
        url : $form.attr('action'),
        type: $form.attr('method'),
        data : data,
        complete: function(html) {
             // Erreur 500?
            $('.annonce_subcategory').replaceWith(
               $(html.responseText).find('.annonce_subcategory')
            );
        }
    });
})

// Ajax categories select
$(document).on('keyup', '#boutique_city', function () {
    let $field = $(this)
    let $form = $field.closest('form')
    let data = $field.val()
    let search = 'postcode'
    if (isNaN(data) ){
        search = 'city'
    }
    let endpoint = 'https://api-adresse.data.gouv.fr'
    $.ajax({
        url: endpoint + "/search/?&q="+search+"=" + data + "&limit=3",
        contentType: "application/json",
        dataType: 'json',
        success: function(result){
            let resultats = result.features
            // console.log(resultats);
            let resultat = resultats.filter()
            // for ($i = 0 ; $i < resultats.length; $i++){
            //
            //     if (resultats[$i].properties.city === resultats[$i-1].properties.city){
            //         resultats[$i].pop()
            //     }
            //     console.log(resultats[$i].properties.city)
            // }
            // resultats.forEach((resultat) => {
            //     console.log(resultat.properties.city)
            // })
        }
    })
})