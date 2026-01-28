<div class="game-info">
    <h1><?php echo $franchise->getName(); ?></h1>
</div>
<div class="game-input">
    <div class="game-input-sub">
        <input type="text" name="game-text-input" id="game-text-input">
        <button name="btn-game-guess" id="btn-game-guess">ENTER</button>
    </div>
</div>
<div class="game-grid">
    <div class="guesses-grid guesses-header">
    <?php foreach ($columns as $label): ?>
        <div class="cell header-cell"><?= htmlspecialchars($label) ?></div>
    <?php endforeach; ?>
</div>
</div>