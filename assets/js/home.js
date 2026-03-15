$(document).ready(function() {
    //Creating delayed version of filterFranchises
    const debouncedFilter = debounce(function () {
        filterFranchises($(this).val())
    }, 150)

    $('#franchise-search-input').on('keyup', debouncedFilter)
})

function filterFranchises(input) {
    console.log(input)
    if(!input) {
        $('.franchise-grid li').show()
        return
    }

    input = input.toLowerCase().trim()

    $('.franchise-grid li').each(function() {
        const name = this.dataset.name
        const match = name.startsWith(input) || name.includes(` ${input}`)

        $(this).toggle(match)
    })
}

//Reusable utility function to add delay
function debounce(fn, delay) {
  let timer
  return function (...args) {
    clearTimeout(timer)
    timer = setTimeout(() => fn.apply(this, args), delay)
  }
}