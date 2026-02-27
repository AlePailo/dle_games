$(document).ready(function() {
    $('body').on('click', '#open-help-menu-btn', showHelpMenu)
    $('body').on('click', '#close-help-menu-btn', hideHelpMenu)
})

function showHelpMenu() {
    $('#help-container').removeClass('hidden')
}

function hideHelpMenu() {
    $('#help-container').addClass('hidden')
}