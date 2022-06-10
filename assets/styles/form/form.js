$(document).on('change', '#annonce_category', function () {

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
            $('#annonce_subcategory').replaceWith(
                $(html.responseText).find('#annonce_subcategory')
            );
        }
    });
})