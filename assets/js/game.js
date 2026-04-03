;(() => {
    const GAME_DATA = window.GAME_DATA  // riferimento diretto, non copia
    GAME_DATA.suggestionsIndex = -1
    delete window.GAME_DATA

    // ================================================
    // INIT & GAME STATE
    // ================================================

    function startGame(characters) {
        $('.game-table__guesses').empty()
        $('#game-text-input').val('')
        $('#game-recap-container').addClass('hidden')
        $('details').removeAttr('open')
        GAME_DATA.stats = getStats()
        GAME_DATA.gameState = {
            solution: getRandomChar(characters),
            guesses: []
        }
        GAME_DATA.charactersClone = { ...GAME_DATA.characters }
    }

    function restoreGame(gameState, characters, basePath) {
        GAME_DATA.gameState = gameState
        const guesses = gameState.guesses
        GAME_DATA.charactersClone = {}
        for (const [key, value] of Object.entries(GAME_DATA.characters)) {
            if (!guesses.includes(key)) GAME_DATA.charactersClone[key] = value
        }

        guesses.forEach(guess => {
            const guessRow = buildGuessUI(guess, GAME_DATA.gameState.solution, characters, basePath)
            $('.game-table__guesses').prepend(guessRow)
            hideSuggestions()
            if ($('#giveup-btn').attr('disabled')) $('#giveup-btn').attr('disabled', false)
        })
    }

    function getRandomChar(characters) {
        const values = Object.values(characters)
        return values[Math.floor(Math.random() * values.length)]
    }


    // ================================================
    // GUESS HANDLING
    // ================================================

    function handleGuessInput(inputTextBox, suggestions, characters, basePath, guess = suggestions.children().eq(0)) {
        guess = guess.data('name')
        tryGuess(guess, characters, GAME_DATA.charactersClone, GAME_DATA.gameState.solution, basePath)
        inputTextBox.val('')
        inputTextBox.focus()
        $('.game-text-input-clear').hide()
    }

    function tryGuess(charName, characters, charactersClone, randomChar, basePath) {
        delete charactersClone[charName]
        const guessRow = buildGuessUI(charName, randomChar, characters, basePath)
        $('.game-table__guesses').prepend(guessRow)
        hideSuggestions()
        if ($('#giveup-btn').attr('disabled')) $('#giveup-btn').attr('disabled', false)

        addGuessToSavedGame(GAME_DATA.gameSlug, charName)
        if (charName === randomChar['name']) endGame(GAME_DATA.gameSlug, 'Win', GAME_DATA.gameState.guesses.length)
    }

    function buildGuessUI(charName, solution, characters, basePath) {
        const $guessRow = $('<tr></tr>')
        Object.entries(characters[charName]).forEach(([attribute, value], index) => {
            const delay = index * 100
            const $attribute = $('<td></td>')

            if (attribute === 'image_url') {
                const $img = $('<img>').attr('src', `${basePath}/assets/img/characters_icons/${GAME_DATA.gameSlug}/${value}`)
                $attribute.addClass('table-cell')
                $attribute.append($img)
            } else {
                $attribute.addClass('table-cell').text(value)
                const result = (value === solution[attribute]) ? 'correct' : 'wrong'
                $attribute.attr('data-guess-result', result)
            }

            $attribute.css('animation-delay', `${delay}ms`)
            $guessRow.append($attribute)
        })
        return $guessRow
    }


    // ================================================
    // AUTOCOMPLETE
    // ================================================

    function findMatches(characters, input) {
        return Object.keys(characters).filter(val =>
            val.toLowerCase().startsWith(input.toLowerCase()) ||
            val.toLowerCase().includes(` ${input.toLowerCase()}`)
        )
    }

    function buildAutoComplete(characters, matches, basePath, $suggestions) {
        showSuggestions()

        if (!matches.length) {
            const $noSuggestions = $('<p></p>')
                .text('No matches')
                .addClass('no-suggestions')
            $suggestions.append($noSuggestions)
            return
        }

        matches.forEach(char => {
            const charName = characters[char].name

            const $suggestion = $('<div></div>')
                .addClass('suggestion')
                .attr({
                    role: 'option',
                    id: `suggestion-${char}`,
                    'data-name': charName,
                    'aria-selected': 'false',
                    'aria-label': charName
                })

            const $image = $('<img>').attr({
                src: `${basePath}/assets/img/characters_icons/${GAME_DATA.gameSlug}/${characters[char].image_url}`,
                alt: '',
                'aria-hidden': 'true'
            })

            const $name = $('<p></p>')
                .text(charName)
                .attr('aria-hidden', 'true')

            $suggestion.append($image, $name)
            $suggestions.append($suggestion)
        })
    }

    function showSuggestions() {
        $('.suggestions').show().scrollTop(0)
        $('#game-text-input').attr('aria-expanded', 'true')
    }

    function hideSuggestions() {
        $('.suggestions').empty().hide()
        $('#game-text-input')
            .attr('aria-expanded', 'false')
            .attr('aria-activedescendant', '')
        GAME_DATA.suggestionsIndex = -1
    }

    function handleArrowNavigation(e, $suggestions) {
        const $items = $suggestions.children()
        if ($items.length === 0) return

        if (e.key === 'ArrowDown') {
            let newIndex = GAME_DATA.suggestionsIndex + 1
            if (newIndex >= $items.length) newIndex = 0
            updateSuggestionSelection($suggestions, newIndex)
        }
        if (e.key === 'ArrowUp') {
            let newIndex = GAME_DATA.suggestionsIndex - 1
            if (newIndex < 0) newIndex = $items.length - 1
            updateSuggestionSelection($suggestions, newIndex)
        }
    }

    function updateSuggestionSelection($suggestions, newIndex) {
        const $items = $suggestions.children()

        $items.eq(newIndex)[0].scrollIntoView({ block: 'nearest' })
        $items.removeClass('selected').attr('aria-selected', 'false')

        if (newIndex >= 0 && newIndex < $items.length) {
            $items.eq(newIndex).addClass('selected').attr('aria-selected', 'false')
            $('#game-text-input').attr('aria-activedescendant', $items.eq(newIndex).attr('id'))
        }

        GAME_DATA.suggestionsIndex = newIndex
    }


    // ================================================
    // END GAME & RECAP
    // ================================================

    function endGame(slug, result, guessesCount) {
        const oldStats = getStats()
        const updatedStats = updateLocalStorage(slug, oldStats, result, guessesCount)
        generateRecap(updatedStats)
        buildSolutionTable()
        removeSavedGame(slug)
        $('#giveup-btn').attr('disabled', true)
    }

    function generateRecap(stats) {
        const solution = GAME_DATA.gameState.solution
        $('#solution-name span').text(solution.name)
        $('#solution-img').attr('src', `${GAME_DATA.basePath}/assets/img/characters_icons/${GAME_DATA.gameSlug}/${solution.image_url}`)

        Array.from($('.stats-row').children('p')).forEach(p =>
            $(p).children('span').text(stats[$(p).attr('data-stats-link')])
        )
        $('#game-recap-container').removeClass('hidden')
    }

    function buildSolutionTable() {
        const $solutionTable = $('#solution-infos-table')
        $solutionTable.empty()
        const excludedColumns = ['name', 'image_url']

        for (const attribute of Object.keys(GAME_DATA.gameState.solution)) {
            if (excludedColumns.includes(attribute)) continue
            const $row = $('<tr></tr>').addClass('solution-infos-table-row')
            let formattedAttr = attribute.charAt(0).toUpperCase() + attribute.slice(1)
            formattedAttr = formattedAttr.replace('_', ' ')
            const $attr = $('<td></td>').text(formattedAttr).addClass('solution-infos-table-attribute')
            const $value = $('<td></td>').text(GAME_DATA.gameState.solution[attribute]).addClass('solution-infos-table-value')
            $row.append($attr, $value)
            $solutionTable.append($row)
        }
    }


    // ================================================
    // PERSISTENCE
    // ================================================

    function getStats() {
        const raw = localStorage.getItem('dle_games_stats')
        if (!raw) return { version: 1, franchises: {} }
        return JSON.parse(raw)
    }

    function setupFranchise(stats, slug) {
        if (stats.franchises[slug]) return
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

    function updateLocalStorage(slug, generalStats, result, guessesCount) {
        setupFranchise(generalStats, slug)
        const stats = generalStats.franchises[slug]
        stats.played++

        if (result === 'Surrender') {
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
        }

        localStorage.setItem('dle_games_stats', JSON.stringify(generalStats))
        return stats
    }

    function getSavedGames() {
        const raw = JSON.parse(localStorage.getItem('dle_games_savedGames'))
        if (raw) return raw
        localStorage.setItem('dle_games_savedGames', JSON.stringify({}))
        return {}
    }

    function getSavedGame(slug) {
        return getSavedGames()[slug] ?? null
    }

    function persistSavedGames(slug, gameState) {
        const games = getSavedGames()
        games[slug] = gameState
        localStorage.setItem('dle_games_savedGames', JSON.stringify(games))
    }

    function addGuessToSavedGame(slug, guess) {
        GAME_DATA.gameState.guesses.push(guess)
        persistSavedGames(slug, GAME_DATA.gameState)
    }

    function removeSavedGame(slug) {
        const games = getSavedGames()
        delete games[slug]
        localStorage.setItem('dle_games_savedGames', JSON.stringify(games))
    }


    // ================================================
    // BOOTSTRAP
    // ================================================

    $(document).ready(function () {
        const savedGame = getSavedGame(GAME_DATA.gameSlug)
        if (savedGame) {
            restoreGame(savedGame, GAME_DATA.characters, GAME_DATA.basePath)
        } else {
            startGame(GAME_DATA.characters)
        }

        const $suggestions = $('.suggestions')
        const $textInput = $('#game-text-input')

        $('body').on('mouseenter', '.suggestion', function () {
            $('.suggestion').removeClass('selected')
            $(this).addClass('selected')
            GAME_DATA.suggestionsIndex = -1
        })

        $('body').on('keydown', '#game-text-input', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault()
            }
        })

        $('body').on('keyup', '#game-text-input', function (e) {
            const currentVal = $(this).val()

            if (currentVal === '') {
                hideSuggestions()
                $('.game-text-input-clear').hide()
                return
            }

            if (e.key === 'Enter') {
                const $items = $suggestions.children()
                if (GAME_DATA.suggestionsIndex >= 0) {
                    const selectedSuggestion = $items.eq(GAME_DATA.suggestionsIndex)
                    handleGuessInput($textInput, $suggestions, GAME_DATA.characters, GAME_DATA.basePath, selectedSuggestion)
                    return
                }
                handleGuessInput($textInput, $suggestions, GAME_DATA.characters, GAME_DATA.basePath)
                return
            }

            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault()
                handleArrowNavigation(e, $suggestions)
                return
            }

            GAME_DATA.suggestionsIndex = -1
            $suggestions.empty()
            $('.game-text-input-clear').show()
            const matches = findMatches(GAME_DATA.charactersClone, currentVal)
            buildAutoComplete(GAME_DATA.charactersClone, matches, GAME_DATA.basePath, $suggestions)
        })

        $('body').on('click', '#btn-game-guess', function () {
            if ($textInput.val() === '') return
            handleGuessInput($textInput, $suggestions, GAME_DATA.characters, GAME_DATA.basePath)
        })

        $('body').on('click', '.suggestion', function () {
            tryGuess($(this).text(), GAME_DATA.characters, GAME_DATA.charactersClone, GAME_DATA.gameState.solution, GAME_DATA.basePath)
            $textInput.val('')
            $textInput.focus()
            $('.game-text-input-clear').hide()
        })

        $(document).on('click', function (e) {
            const container = document.querySelector('.game-text-input-container')
            if (!container.contains(e.target)) hideSuggestions()
        })

        $('body').on('click', '.game-text-input-clear', function () {
            $('#game-text-input').val('')
            hideSuggestions()
            $('.game-text-input-clear').hide()
        })

        $('body').on('focus', '#game-text-input', function () {
            const $suggestions = $('.suggestions')
            $suggestions.empty()
            const currentVal = $(this).val()
            if (currentVal === '') {
                $('.game-text-input-clear').hide()
                return
            }
            $('.game-text-input-clear').show()
            const matches = findMatches(GAME_DATA.characters, currentVal)
            buildAutoComplete(GAME_DATA.characters, matches, GAME_DATA.basePath, $suggestions)
        })

        $('body').on('click', '#giveup-btn', function () {
            endGame(GAME_DATA.gameSlug, 'Surrender', GAME_DATA.gameState.guesses.length)
        })

        $('body').on('click', '#new-game-btn', function () {
            startGame(GAME_DATA.characters)
        })
    })

})()