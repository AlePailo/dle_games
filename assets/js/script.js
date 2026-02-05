$(document).ready(function() {
    //localStorage.removeItem('dle_games_stats')
    console.log(localStorage)
    const { characters, columns, basePath } = GAME_DATA
    startGame(characters)
    //let {randomChar, charactersClone} = GAME_DATA
    const $suggestions = $('.suggestions')
    const $textInput = $('#game-text-input')
    
    $('body').on('keyup', '#game-text-input', function(e) {
        let currentVal = $(this).val()
        if(e.key === 'Enter' || e.keyCode === 13) {
            let guess = $suggestions.children(':first').children('p').text()
            tryGuess(guess, characters, GAME_DATA.charactersClone, GAME_DATA.randomChar, basePath)
            $textInput.val('')
            $('.x').hide()
            return
        }

        $suggestions.empty()
        if(currentVal === '') {
            $('.x').hide()
            return
        }
        $('.x').show()
        const matches = findMatches(GAME_DATA.charactersClone, currentVal)
        buildAutoComplete(GAME_DATA.charactersClone, matches, basePath, $suggestions)
    })

    $('body').on('click', '#btn-game-guess', function() {
        /*$textInput = $('#game-text-input')
        let guess = $textInput.val()*/
        if($textInput.val() === '') return
        let guess = $suggestions.children(':first').children('p').text()
        tryGuess(guess, characters, GAME_DATA.charactersClone, GAME_DATA.randomChar, basePath)
        $textInput.val('')
        $textInput.focus()
        $('.x').hide()
    })

    $('body').on('click', '.suggestion', function() {
        tryGuess($(this).text(), characters, GAME_DATA.charactersClone, GAME_DATA.randomChar, basePath)
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

    $('body').on('click', '#giveup-btn', function() {
        endGame(GAME_DATA.gameSlug, 'Surrender', GAME_DATA.gameState.guessesCount)
    })

    $('body').on('click', '#new-game-btn', function() {
        startGame(characters)
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
    if($('#giveup-btn').attr('disabled')) $('#giveup-btn').attr('disabled', false)

    GAME_DATA.gameState.guessesCount++ 
    if(charName === randomChar['name']) endGame(GAME_DATA.gameSlug, 'Win', GAME_DATA.gameState.guessesCount)
}

function showSuggestions() {
    $('.suggestions').show()
}

function hideSuggestions() {
    $('.suggestions').hide()
}

function startGame(characters) {
    $('#game-recap-container').addClass('hidden')
    GAME_DATA.stats = getStats()
    GAME_DATA.gameState = {
        guessesCount: 0,
        result: null
    }
    GAME_DATA.randomChar = getRandomChar(characters)
    GAME_DATA.charactersClone = {...GAME_DATA.characters}
    console.log(GAME_DATA)
}

function updateLocalStorage(slug, result, guessesCount) {
    setupFranchise(GAME_DATA.stats, slug)

    const stats = GAME_DATA.stats.franchises[slug]
    stats.played++

    if(result === 'Surrender') {
        stats.surrenders++
        stats.currentStreak = 0
    } else {
        stats.wins++
        stats.totalGuesses += guessesCount
        stats.averageGuesses = (stats.totalGuesses / stats.wins).toFixed(2)
        stats.currentStreak++

        stats.bestGuesses = Math.min(stats.bestGuesses ?? guessesCount, guessesCount)
        stats.worstGuesses = Math.max(stats.worstGuesses ?? guessesCount, guessesCount)
        stats.bestStreak = Math.max(stats.bestStreak, stats.currentStreak)

        /*if(guessesCount < stats.bestGuesses) stats.bestGuesses = guessesCount
        if(guessesCount > stats.worstGuesses) stats.worstGuesses = guessesCount
        if(stats.currentStreak > stats.bestStreak) stats.bestStreak = stats.currentStreak*/
    }

    localStorage.setItem('dle_games_stats', JSON.stringify(GAME_DATA.stats))

    return stats

}

function getStats() {
    const raw = localStorage.getItem('dle_games_stats')

    if (!raw) {
        return {
        version: 1,
        franchises: {}
        }
    }

    return JSON.parse(raw);
}

function setupFranchise(stats, slug) {
    if(stats.franchises[slug]) return

    stats.franchises[slug] = {
        played: 0,
        wins: 0,
        surrenders: 0,
        totalGuesses: 0,
        averageGuesses: 0,

        bestGuesses: null,
        worstGuesses: null,

        currentStreak: 0,
        bestStreak: 0
    }
}

function endGame(slug, result, guessesCount) {
    const updatedStats = updateLocalStorage(slug, result, guessesCount)
    generateRecap(updatedStats)
    $('.guesses').empty()
    $('#giveup-btn').attr('disabled', true)
}

function generateRecap(stats) {
    const solution = GAME_DATA.randomChar
    $('#solution-name span').text(solution.name)
    $('#solution-img').attr('src', `${GAME_DATA.basePath}/assets/img/characters_icons/${solution.image_url}`)

    Array.from($('#player-stats').children('p')).forEach(p => $(p).children('span').text(stats[$(p).attr('data-stats-link')]))
    $('#game-recap-container').removeClass('hidden')
}