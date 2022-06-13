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
             // Je ne récupere pas la meme chose sur edit et sur new -> l'input disparait sur edit -> Erreur 500?
            $('.annonce_subcategory').replaceWith(
               $(html.responseText).find('.annonce_subcategory')
            );
        }
    });
})