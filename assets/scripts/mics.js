export default function ($) {
    $('tr[data-href]').click(function () {
        const loc = $(this).attr('data-href')
        window.location.href = loc
    })

    $('.flash-alert button').click(function () {
        $(this).parent().hide()
    })

    $('form[name=car_search_form]')
        .find('button[type=submit]')
        .html('<i class="fa-solid fa-magnifying-glass"></i>')
}