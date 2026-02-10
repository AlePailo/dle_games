$(document).ready(function() {
    //localStorage.removeItem('dle_games_stats')
    //localStorage.removeItem('dle_games_savedGames')
    console.log(localStorage)
    const { characters, columns, basePath } = GAME_DATA
    
    const savedGame = getSavedGame(GAME_DATA.gameSlug)

    if(savedGame) {
        restoreGame(savedGame, characters, basePath)
    } else {
        startGame(characters)
    }

    const $suggestions = $('.suggestions')
    const $textInput = $('#game-text-input')
    
    $('body').on('keyup', '#game-text-input', function(e) {
        let currentVal = $(this).val()

        if(currentVal === '') {
            hideSuggestions()
            $('.x').hide()
            return
        }

        if(e.key === 'Enter' || e.keyCode === 13) {
            handleGuessInput($textInput, $suggestions, characters, basePath)
            return
        }

        
        $suggestions.empty()
        $('.x').show()
        const matches = findMatches(GAME_DATA.charactersClone, currentVal)
        buildAutoComplete(GAME_DATA.charactersClone, matches, basePath, $suggestions)
    })

    $('body').on('click', '#btn-game-guess', function() {
        if($textInput.val() === '') return
        handleGuessInput($textInput, $suggestions, characters, basePath)
    })

    $('body').on('click', '.suggestion', function() {
        tryGuess($(this).text(), characters, GAME_DATA.charactersClone, GAME_DATA.gameState.solution, basePath)
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
        endGame(GAME_DATA.gameSlug, 'Surrender', GAME_DATA.gameState.guesses.length)
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
    buildGuessUI(charName, randomChar, characters, basePath)
    delete charactersClone[charName]
    $('.guesses').prepend($guessRow)
    hideSuggestions()
    if($('#giveup-btn').attr('disabled')) $('#giveup-btn').attr('disabled', false)

    addGuessToSavedGame(GAME_DATA.gameSlug, charName)
    if(charName === randomChar['name']) endGame(GAME_DATA.gameSlug, 'Win', GAME_DATA.gameState.guesses.length)
}

function showSuggestions() {
    $('.suggestions').show()
}

function hideSuggestions() {
    $('.suggestions').empty()
    $('.suggestions').hide()
}

function startGame(characters) {
    $('.guesses').empty()
    $('#game-text-input').val('')
    $('#game-recap-container').addClass('hidden')
    GAME_DATA.stats = getStats()
    GAME_DATA.gameState = {
        solution: getRandomChar(characters),
        guesses: []
    }
    GAME_DATA.charactersClone = {...GAME_DATA.characters}
    console.log(GAME_DATA)
}

function updateLocalStorage(slug, generalStats, result, guessesCount) {
    setupFranchise(generalStats, slug)

    const stats = generalStats.franchises[slug]
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

    localStorage.setItem('dle_games_stats', JSON.stringify(generalStats))

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
    const oldStats = getStats()
    const updatedStats = updateLocalStorage(slug, oldStats, result, guessesCount)
    generateRecap(updatedStats)
    removeSavedGame(slug)
    $('#giveup-btn').attr('disabled', true)
}

function generateRecap(stats) {
    const solution = GAME_DATA.gameState.solution
    $('#solution-name span').text(solution.name)
    $('#solution-img').attr('src', `${GAME_DATA.basePath}/assets/img/characters_icons/${solution.image_url}`)

    Array.from($('#player-stats').children('p')).forEach(p => $(p).children('span').text(stats[$(p).attr('data-stats-link')]))
    $('#game-recap-container').removeClass('hidden')
}





function getSavedGames() {
    const raw = JSON.parse(localStorage.getItem('dle_games_savedGames'))

    if(raw) return raw

    localStorage.setItem('dle_games_savedGames', JSON.stringify({}))
    return {}
}


function getSavedGame(slug) {
    const games = getSavedGames()
    return games[slug] ?? null
}


function restoreGame(gameState, characters, basePath) {
    GAME_DATA.gameState = gameState
    const guesses = gameState.guesses
    GAME_DATA.charactersClone = {}
    for(const [key, value] of Object.entries(GAME_DATA.characters)) {
        if(!guesses.includes(key)) GAME_DATA.charactersClone[key] = value
    }
    console.log(GAME_DATA.charactersClone)

    guesses.forEach(guess => {
        buildGuessUI(guess, GAME_DATA.gameState.solution, characters, basePath)
        $('.guesses').prepend($guessRow)
        hideSuggestions()
        if($('#giveup-btn').attr('disabled')) $('#giveup-btn').attr('disabled', false)
    })
}

function persistSavedGames(slug, gameState) {
    const games = getSavedGames()
    games[slug] = gameState
    console.log(games)
    localStorage.setItem('dle_games_savedGames', JSON.stringify(games))
}

function addGuessToSavedGame(slug, guess) {
    GAME_DATA.gameState.guesses.push(guess)
    persistSavedGames(slug, GAME_DATA.gameState)
}




// ON WIN / GIVE UP

function removeSavedGame(slug) {
    const games =  getSavedGames()
    delete games[slug]
    localStorage.setItem('dle_games_savedGames', JSON.stringify(games))
}




function handleGuessInput(inputTextBox, suggestions, characters, basePath) {
    let guess = suggestions.children(':first').children('p').text()
    if(!guess) return
    tryGuess(guess, characters, GAME_DATA.charactersClone, GAME_DATA.gameState.solution, basePath)
    inputTextBox.val('')
    inputTextBox.focus()
    $('.x').hide()
}


function buildGuessUI(charName, solution, characters, basePath) {
    $guessRow = $('<tr></tr>')
        for(attribute in characters[charName]){
            if(attribute === 'image_url') {
                $img = $('<img>').attr('src', `${basePath}/assets/img/characters_icons/${characters[charName][attribute]}`)
                $attribute = $('<td></td>').addClass('table-cell')
                $attribute.append($img)
            } else {
                $attribute = $('<td></td>').addClass('table-cell').text(characters[charName][attribute])
                if(characters[charName][attribute] === solution[attribute]) {
                    $attribute.addClass('correct')
                } else {
                    $attribute.addClass('wrong')
                }
            }
            $guessRow.append($attribute)
        }
}