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


// Ajax API Request for City Coordinates

const postcode_input = $('#boutique_postcode');
const city_select = $('#boutique_city');
const coordinates_box = $('#boutique_coordinates');
const coordinates = [];

// Fill the city suggestions select using postcode
postcode_input.on('keyup', function () {
    let $field = $(this)
    let data = $field.val()
    const citySuggest = [];
    city_select.empty();
    let endpoint = 'https://api-adresse.data.gouv.fr/search/?q=postcode='+ data + "&limit=3";
    if (data.length !== 5){
        if (data.length > 5 ){
            citySuggest.push('<option>Code postal trop long !</option>')
        }else{
            citySuggest.push('<option>Renseignez un code postal</option>')
        }
        coordinates_box.val(null);
    }
    else {
        $.ajax({
            url: endpoint,
            contentType: "application/json",
            dataType: 'json',
            success: function(result){
                city_select.empty();
                const resultats = result.features
                if (resultats.length !== 0 ){
                    for ( let i = 0; i < resultats.length ; i++)
                    {
                        let city_name = resultats[i].properties.city;
                        let li = '<option id="'+city_name+'">'+city_name+' </option>'
                        if (!citySuggest.includes(li)){
                            citySuggest.push(li) ;
                        }else{
                            li = '<option>Le code postal ne correspond pas</option>'
                        }
                    }
                }else{
                    citySuggest.push('<option>Code postal inconnu</option>')
                }
                city_select.append(citySuggest);
                let coordinate = [resultats[0].geometry.coordinates[0],resultats[0].geometry.coordinates[1],resultats[0].properties.city ];
                coordinates.length = 0;
                coordinates.push(coordinate);
                coordinates_box.val(coordinates)
            }
        })

    }
    city_select.append(citySuggest);
})

// Get the new coordinates on city's select change
city_select.on('change', function () {
    coordinates_box.val(null);
    coordinates.length = 0;
    let $field = $(this)
    let data = $field.val()
    let postcode = postcode_input.val()
    console.log(data);
    let endpoint = 'https://api-adresse.data.gouv.fr/search/?q=city=' + data + "&postcode="+postcode+"&limit=1";
    $.ajax({
        url: endpoint,
        success: function (result) {
            const resultats = result.features;
            let coordinate = [resultats[0].geometry.coordinates[0], resultats[0].geometry.coordinates[1], resultats[0].properties.city];
            coordinates.push(coordinate);
            coordinates_box.val(coordinates)
        }
    })
})