<!--<section class="home">-->
<h1 class="home__title">Choose a game</h1>

<div class="franchise-grid">
    <?php foreach($franchisesList as $franchise): ?>

    <?php 
        $isActive = $franchise->getActive();
        $isNew = $franchise->isNew();

        $imgContainerClasses = 'franchise-card__image-container';
        
        if($isActive) {
            $tag = 'a';
            $href = $basePath . '/' . htmlspecialchars($franchise->getSlug());
            if($isNew) {
                $badgeText = 'NEW';
            }
        } else {
            $tag = 'div';
            $imgContainerClasses .= ' disabled';
            $badgeText = 'DISABLED';
        }
    ?>

        <<?= $tag ?> class="franchise-card" <?= $isActive ? 'href="' . $href . '"' : '' ?>>
            <div class="<?= $imgContainerClasses ?>">
                <?php if($isNew || !$isActive): ?>
                    <span class="franchise-card__badge"><?= $badgeText ?></span>
                <?php endif; ?>
                <img class="franchise-card__image" src="<?= $basePath ?>/assets/img/games_icons/<?= htmlspecialchars($franchise->getIconUrl()) ?>" alt="<?= htmlspecialchars($franchise->getName()) ?>" loading="lazy">
            </div>
            <h3 class="franchise-card__name"><?= htmlspecialchars(strtoupper($franchise->getName())) ?></h3>
        </<?= $tag ?>>
    <?php endforeach; ?>
</div>
<!--</section>-->