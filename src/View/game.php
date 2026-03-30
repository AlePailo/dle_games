<script>
    window.GAME_DATA = <?= json_encode([
        'columns' => $columns,
        'characters' => $charactersForGame,
        'gameSlug' => $slug,
        'basePath' => $basePath
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?>;
</script>

<section class="game-info flex-center fd-column">
    <h1 class="game-info__title"><?= $title; ?></h1>
    <p class="game-info__description"><?= $metaDescription; ?></p>
    <button id="giveup-btn" disabled>GIVE UP</button>
</section>

<section class="game-input flex-center">
    <div class="game-text-input-container">
        <input type="text" name="game-text-input" id="game-text-input" placeholder="Enter the character name" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="suggestions-list" aria-activedescendant="" aria-haspopup="listbox">
        <button type="button" class="game-text-input-clear" aria-label="Clear input">X</button>
        <div class="suggestions" id="suggestions-list" role="listbox"></div>
    </div>
    <button type="button" name="btn-game-guess" id="btn-game-guess">ENTER</button>
</section>

<section class="game-table-wrapper">
    <table class="game-table">
        <thead>
            <tr>
                <?php foreach ($columns as $column): ?>
                    <th scope="col" class=""><?= htmlspecialchars($column['label']) ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody class="game-table__guesses" aria-live="polite">
            
        </tbody>
    </table>
</section>

<section id="game-recap-container" class="fullscreen-popup-container flex-center hidden" role="dialog" aria-modal="true" aria-labelledby="solution-name">
    <div class="popup-menu">
        <div id="solution-infos" class="flex-center fd-column g1">
            <h3 id="solution-name">The character was: <span></span></h3>
            <img id="solution-img" src="" alt="">
            <table id="solution-infos-table">
                <tbody></tbody>
            </table>
        </div>
        <div id="player-stats">
            <details>
                <summary>Show player stats</summary>
                <!--<h3>Your stats:</h3>-->
                <div class="stats-row flex-center">
                    <p data-stats-link="played" class="flex-center fd-column">Total games<span></span></p>
                    <p data-stats-link="wins" class="flex-center fd-column">Wins<span></span></p>
                    <p data-stats-link="surrenders" class="flex-center fd-column">Surrenders<span></span></p>
                </div>
                <div class="stats-row flex-center">
                    <p data-stats-link="averageGuesses" class="flex-center fd-column">Avg. guesses<span></span></p>
                    <p data-stats-link="bestGuesses" class="flex-center fd-column">Best guesses<span></span></p>
                    <p data-stats-link="worstGuesses" class="flex-center fd-column">Worst guesses<span></span></p>
                </div>
                <div class="stats-row flex-center">
                    <p data-stats-link="bestStreak" class="flex-center fd-column">Best streak<span></span></p>
                    <p data-stats-link="currentStreak" class="flex-center fd-column">Current streak<span></span></p>
                </div>
            </details>
        </div>
        <div id="end-game-user-controls" class="flex-center g1">
            <button id="new-game-btn" class="popup-menu-btn end-game-user-controls__btn" aria-label="Play Again">
                <svg class="end-game-user-controls__image" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,2c-0.72127,-0.0102 -1.39216,0.36875 -1.75578,0.99175c-0.36361,0.623 -0.36361,1.39351 0,2.01651c0.36361,0.623 1.0345,1.00195 1.75578,0.99175c10.51712,0 19,8.48288 19,19c0,10.51712 -8.48288,19 -19,19c-10.51712,0 -19,-8.48288 -19,-19c0,-5.4758 2.30802,-10.39189 6,-13.85547v3.85547c-0.0102,0.72127 0.36875,1.39216 0.99175,1.75578c0.623,0.36361 1.39351,0.36361 2.01651,0c0.623,-0.36361 1.00195,-1.0345 0.99175,-1.75578v-11h-11c-0.72127,-0.0102 -1.39216,0.36875 -1.75578,0.99175c-0.36361,0.623 -0.36361,1.39351 0,2.01651c0.36361,0.623 1.0345,1.00195 1.75578,0.99175h4.52539c-4.61869,4.20948 -7.52539,10.27232 -7.52539,17c0,12.67888 10.32112,23 23,23c12.67888,0 23,-10.32112 23,-23c0,-12.67888 -10.32112,-23 -23,-23z"></path></g></g>
                </svg>
            </button>
            <a href="<?= $basePath ?>" class="popup-menu-btn end-game-user-controls__btn" aria-label="Return to Home">
                <svg class="end-game-user-controls__image" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,1.05078c-0.2175,0 -0.43414,0.06898 -0.61914,0.20898l-23,17.95117c-0.43,0.34 -0.50992,0.9682 -0.16992,1.4082c0.34,0.43 0.9682,0.50992 1.4082,0.16992l1.38086,-1.07812v26.28906c0,0.55 0.45,1 1,1h14v-18h12v18h14c0.55,0 1,-0.45 1,-1v-26.28906l1.38086,1.07812c0.19,0.14 0.39914,0.21094 0.61914,0.21094c0.3,0 0.58906,-0.13086 0.78906,-0.38086c0.34,-0.44 0.26008,-1.0682 -0.16992,-1.4082l-23,-17.95117c-0.185,-0.14 -0.40164,-0.20898 -0.61914,-0.20898zM35,5v1.05078l6,4.67969v-5.73047z"></path></g></g>
                </svg>
            </a>
        </div>
    </div>
</section>