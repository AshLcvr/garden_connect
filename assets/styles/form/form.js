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

const adress_input    = $('#boutique_adress');
const adress          = adress_input.val();
const search_input    = $('#boutique_search');
const city_ul         = $('#city_suggest');
const city_input      = $('#boutique_city');
const postcode_input  = $('#boutique_postcode');
const citycode_input  = $('#boutique_citycode');

search_input.on('keyup', function () {
    let city = search_input.val();
    let citySuggest = [];
    city_input.val('')
    city_ul.empty();
    city_ul.addClass('hidden')
    isNaN(city)? search = 'nom' : search = 'codePostal';
    if (isNaN(city) && city.length >= 3 || !isNaN(city) && city.length === 5){
        $.ajax({
            url: 'https://geo.api.gouv.fr/communes?'+search+'='+ city +'&limit=7 ',
            contentType: "application/json",
            dataType: 'json',
            success: function(result){
                city_ul.empty();
                if (result.length > 0){
                    city_ul.removeClass('hidden')
                    for (let i = 0; i < result.length; i++)
                    {
                        let city_name     = result[i].nom;
                        let city_code     = result[i].code;
                        let city_postcode = '';
                        if (result[i].codesPostaux.length > 1){
                            city_postcode = result[i].codesPostaux[1];
                        }else{
                            city_postcode = result[i].codesPostaux[0];
                        }
                        let li = '<li data-city="'+city_name+'" data-postcode="'+city_postcode+'" data-citycode="'+city_code+'" > '+city_name+' ('+city_postcode+')</li>'
                        citySuggest.push(li)
                    }
                }
                else{
                    city_ul.addClass('hidden')
                }
                city_ul.append(citySuggest);
                let li = city_ul.children();

                li.on('click',function () {
                    city_ul.addClass('hidden')
                    city_ul.empty();
                    let city_infos = $(this).html();
                    search_input.val(city_infos);
                    let city_name      = $(this).attr('data-city')
                    let city_postcode  = $(this).attr('data-postcode')
                    let city_code      = $(this).attr('data-citycode')
                    city_input.val(city_name)
                    postcode_input.val(city_postcode)
                    citycode_input.val(city_code)
                })
            }
        })
    }
});



