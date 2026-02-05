<!--<section class="home">-->
    <h1 class="home__title">Choose a game</h1>

    <div class="franchise-grid">
        <?php foreach($franchisesList as $franchise): ?>
            <a class="franchise-card" href="<?= $basePath ?>/<?= htmlspecialchars($franchise->getSlug()) ?>">
                <div class="franchise-card__image-container <?php if(!$franchise->getActive()): ?> disabled<?php endif; ?>"><img class="franchise-card__image" src="<?= $basePath ?>/assets/img/games_icons/<?= htmlspecialchars($franchise->getImageUrl()) ?>" alt="<?= htmlspecialchars($franchise->getName()) ?>" loading="lazy"></div>
                <h3 class="franchise-card__name"><?= htmlspecialchars(strtoupper($franchise->getName())) ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
<!--</section>-->