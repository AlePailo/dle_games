$(document).ready(function() {
    $('body').on('keyup', '#franchise-search-input', function() {
        const val = $(this).val()
        filterFranchises(val)
    })
})

function filterFranchises(input) {
    input = input.toLowerCase().trim()
    const $target = $('.franchise-grid').children()
    $target.filter(function() {
        console.log($(this))
        //$(this).toggle($(this).children('h3').text().toLowerCase().indexOf(input) > -1)
        $(this).toggle($(this).children('h3').text().toLowerCase().startsWith(input) || $(this).children('h3').text().toLowerCase().includes(` ${input}`))
    })
}