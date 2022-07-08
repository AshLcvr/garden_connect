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

const city_input = $('#boutique_city');
const city_select = $('#city_suggest');
const coordinates_box = $('#boutique_coordinates');
// const coordinates = [];
// const adress = $('#boutique_adress').val();
let search = '';

// Fill the city suggestions select using postcode
city_input.on('keyup', function () {
    let city = city_input.val()
    let citySuggest = [];
    city_select.empty();
    isNaN(city)? search = 'nom' : search = 'codePostal';
     let endpoint = '   https://geo.api.gouv.fr/communes?'+search+'='+ city +'&limit=5';
    console.log(endpoint)
    if (city.length >= 3){
        $.ajax({
            url: endpoint,
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
                        let li = '<li class="li" value="'+city_name+'">'+city_name+' ('+city_postcode+')</li>'
                        citySuggest.push(li)
                    }
                }else{
                    let li = '<li>Ville ou code postal incorrects</li>'
                    citySuggest.push(li)
                }
                city_select.append(citySuggest)


                ///////////////////////////////////////////
                $('.li').on('click',function () {
                    console.log( $('.li').value)
                    // city_input.val()
                })
                ///////////////////////////////////////////





            }
        })
    }
})



