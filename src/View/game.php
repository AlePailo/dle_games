<script>
    window.GAME_DATA = <?= json_encode([
        'columns' => $columns,
        'characters' => $charactersForGame,
        'gameSlug' => $slug,
        'basePath' => $basePath
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?>;
</script>

<div class="game-info">
    <h1><?php echo $franchise->getName(); ?></h1>
    <h3><?php echo $franchise->getDescription(); ?></h3>
    <button id="giveup-btn" disabled>GIVE UP</button>
</div>
<div class="game-input">
    <div class="game-input-sub">
        <div class="game-text-input-container">
            <input type="text" name="game-text-input" id="game-text-input" placeholder="Enter the character name">
            <p class="x">X</p>
            <div class="suggestions"></div>
        </div>
        <button name="btn-game-guess" id="btn-game-guess">ENTER</button>
    </div>
</div>

<div class="game-table-wrapper">
    <table class="game-table">
        <thead>
            <tr>
                <?php foreach ($columns as $label): ?>
                    <th class=""><?= htmlspecialchars($label) ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody class="guesses">
            
        </tbody>
    </table>
</div>

<div id="game-recap-container" class="flex-center hidden">
    <div class="game-recap">
        <div id="solution-infos" class="flex-center fd-column g1">
            <h3 id="solution-name">The character was: <span></span></h3>
            <img id="solution-img" src="">
        </div>
        <div id="player-stats">
            <h3>Your stats:</h3>
            <p data-stats-link="played">Total games played: <span></span></p>
            <p data-stats-link="wins">Wins: <span></span></p>
            <p data-stats-link="surrenders">Surrenders: <span></span></p>
            <p data-stats-link="averageGuesses">Average guesses: <span></span></p>
            <p data-stats-link="bestGuesses">Best guesses: <span></span></p>
            <p data-stats-link="worstGuesses">Worst guesses: <span></span></p>
            <p data-stats-link="bestStreak">Best streak: <span></span></p>
            <p data-stats-link="currentStreak">Current streak: <span></span></p>
        </div>
        <div id="user-controls" class="flex-center g1">
            <button id="new-game-btn">PLAY AGAIN</button>
            <a href="<?= $basePath ?>"><button>HOME</button></a>
        </div>
    </div>
</div>