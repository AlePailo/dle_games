<script>
    window.GAME_DATA = <?= json_encode([
        'columns' => $columns,
        'characters' => $charactersForGame,
        'basePath' => $basePath
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) ?>;
</script>

<div class="game-info">
    <h1><?php echo $franchise->getName(); ?></h1>
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

