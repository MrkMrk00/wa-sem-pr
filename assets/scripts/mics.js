export default function ($) {
    $('tr[data-href]').click(function () {
        const loc = $(this).attr('data-href')
        window.location.href = loc
    })

    $('.flash-alert button').click(function () {
        $(this).parent().hide()
    })
}