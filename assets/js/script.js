$(document).ready(function() {
    const { characters, columns, basePath } = window.GAME_DATA
    startGame(characters)
    let {randomChar, charactersClone} = window.GAME_DATA
    console.log(randomChar)

    const $suggestions = $('.suggestions')
    const $textInput = $('#game-text-input')
    
    $('body').on('keyup', '#game-text-input', function(e) {
        let currentVal = $(this).val()
        if(currentVal === '') {
            $('.x').hide()
            return
        }
        if(e.key === 'Enter' || e.keyCode === 13) {
            let guess = $suggestions.children(':first').children('p').text()
            tryGuess(guess, characters, charactersClone, randomChar, basePath)
            $textInput.val('')
            $('.x').hide()
            return
        }
        $suggestions.empty()
        $('.x').show()
        const matches = findMatches(charactersClone, currentVal)
        buildAutoComplete(charactersClone, matches, basePath, $suggestions)
    })

    $('body').on('click', '#btn-game-guess', function() {
        /*$textInput = $('#game-text-input')
        let guess = $textInput.val()*/
        let guess = $suggestions.children(':first').children('p').text()
        tryGuess(guess, characters, charactersClone, randomChar, basePath)
        $textInput.val('')
        $textInput.focus()
        $('.x').hide()
    })

    $('body').on('click', '.suggestion', function() {
        tryGuess($(this).text(), characters, charactersClone, randomChar, basePath)
        $textInput.val('')
        $textInput.focus()
        $('.x').hide()
    })

    $(document).on('click', function (e) {
        const container = document.querySelector('.game-text-input-container');

        if (!container.contains(e.target)) {
            hideSuggestions()
        }
    })


    $('body').on('click', '.x', function() {
        $('#game-text-input').val('')
        hideSuggestions()
        $('.x').hide()
    })


    $('body').on('focus', '#game-text-input', function() {
        const $suggestions = $('.suggestions')
        $suggestions.empty()
        let currentVal = $(this).val()
        if(currentVal === '') {
            $('.x').hide()
            return
        }
        $('.x').show()
        const matches = findMatches(characters, currentVal)
        buildAutoComplete(characters, matches, basePath, $suggestions)
    })
})

function getRandomChar(characters) {
    const values = Object.values(characters)
    const prop = values[Math.floor(Math.random() * values.length)]
    return prop
}

function findMatches(characters, input) {
    const matches = Object.keys(characters).filter(val => val.toLowerCase().startsWith(input.toLowerCase()) || val.toLowerCase().includes(` ${input.toLowerCase()}`))
    
    return matches
}

function buildAutoComplete(characters, matches, basePath, $suggestions) {
    showSuggestions()
    matches.forEach(char => {
        const $suggestion = $('<div></div>').addClass('suggestion')
        const $image = $('<img>').attr('src', `${basePath}/assets/img/characters_icons/${characters[char].image_url}`)
        const $name = $('<p></p>').text(characters[char].name)
        $suggestion.append($image)
        $suggestion.append($name)
        $suggestions.append($suggestion)
    })
}

function tryGuess(charName, characters, charactersClone, randomChar, basePath) {
    $guessRow = $('<tr></tr>')
    for(attribute in characters[charName]){
        if(attribute === 'image_url') {
            $img = $('<img>').attr('src', `${basePath}/assets/img/characters_icons/${characters[charName][attribute]}`)
            $attribute = $('<td></td>').addClass('table-cell')
            $attribute.append($img)
        } else {
            $attribute = $('<td></td>').addClass('table-cell').text(characters[charName][attribute])
            if(characters[charName][attribute] === randomChar[attribute]) {
                $attribute.addClass('correct')
            } else {
                $attribute.addClass('wrong')
            }
        }
        $guessRow.append($attribute)
    }
    delete charactersClone[charName]
    $('.guesses').prepend($guessRow)
    hideSuggestions()
}

function showSuggestions() {
    $('.suggestions').show()
}

function hideSuggestions() {
    $('.suggestions').hide()
}

function startGame(characters) {
    GAME_DATA.randomChar = getRandomChar(characters)
    GAME_DATA.charactersClone = {...characters}
}