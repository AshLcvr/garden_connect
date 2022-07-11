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

const search_input    = $('#boutique_search');
const city_input      = $('#boutique_city');
const postcode_input  = $('#boutique_postcode');
const city_select     = $('#city_suggest');
const coordinates_box = $('#boutique_coordinates');
const adress          = $('#boutique_adress').val();
// let search            = '';

search_input.on('keyup', function () {
    let city = search_input.val();
    let citySuggest = [];
    city_select.empty();
    isNaN(city)? search = 'nom' : search = 'codePostal';
    if (city.length >= 3){
        $.ajax({
            url: 'https://geo.api.gouv.fr/communes?'+search+'='+ city +'&limit=7 ',
            contentType: "application/json",
            dataType: 'json',
            success: function(result){
                city_select.empty();
                if (result.length > 0){
                    for (let i = 0; i < result.length; i++)
                    {
                        let city_name     = result[i].nom;
                        let city_postcode = '';
                        if (result[i].codesPostaux.length > 1){
                            city_postcode = result[i].codesPostaux[1];
                        }else{
                            city_postcode = result[i].codesPostaux[0];
                        }
                        let li = '<li class="city_answer" value="'+city_name+'">'+city_name+' ('+city_postcode+')</li>'
                        citySuggest.push(li)
                    }
                }else{
                    let li = '<li>Ville ou code postal incorrects</li>'
                    citySuggest.push(li)
                }
                city_select.append(citySuggest);
                let li = city_select.children();

                li.on('click',function () {
                    let city_infos = $(this).html();
                    search_input.val(city_infos);
                    city_select.empty();
                    // let city_infos_array = search_input.val().split(' ');
                    // let city_name        = city_infos_array[0]
                    // let postcode         = city_infos_array[1].replace( /[^A-Za-z0-9]/g ,'')
                    // city_input.val(city_name)
                    // postcode_input.val(postcode)
                    // let formatedAdress   = decodeURIComponent(  adress.replace( /[^A-Za-z0-9]/g ,'+'))
                    //
                    // let endpoint = '';
                    // if (adress.length > 0){
                    //     endpoint = 'https://api-adresse.data.gouv.fr/search/?q='  + formatedAdress +'&city='+ city_name+  '&postcode=' + postcode +'&limit=1'
                    // }else{
                    //     endpoint = 'https://api-adresse.data.gouv.fr/search/?q=postcode=' + postcode + '&city='+ city_name +'&limit=1'
                    // }
                    // console.log(endpoint)
                    //     $.ajax({
                    //     url: endpoint,
                    //     contentType: "application/json ; charset:ISO-8859-1",
                    //     dataType: 'json',
                    //     success: function (response) {
                    //         console.log(response)
                    //         if (response.features.length > 0){
                    //             let city_props  = response.features[0].geometry.coordinates;
                    //             let coordinates = city_props[0] + city_props[1];
                    //             coordinates_box.val(coordinates);
                    //             console.log( coordinates_box.val())
                    //         }else{
                    //             console.log('Failure')
                    //             city_select.append('<li>L\'adresse ne correspond pas.</li>');
                    //         }
                    //     }
                    // })
                })
            }
        })
    }
});



