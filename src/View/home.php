<!--<section class="home">-->
<h1 class="home__title">Choose a game</h1>
<div class="franchise-search">
    <input type="text" name="franchise-search" id="franchise-search-input" placeholder="Search for a franchise...">
    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
        <g fill="var(--color-bg-main)" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M21,3c-9.39844,0 -17,7.60156 -17,17c0,9.39844 7.60156,17 17,17c3.35547,0 6.46094,-0.98437 9.09375,-2.65625l12.28125,12.28125l4.25,-4.25l-12.125,-12.09375c2.17969,-2.85937 3.5,-6.40234 3.5,-10.28125c0,-9.39844 -7.60156,-17 -17,-17zM21,7c7.19922,0 13,5.80078 13,13c0,7.19922 -5.80078,13 -13,13c-7.19922,0 -13,-5.80078 -13,-13c0,-7.19922 5.80078,-13 13,-13z"></path></g></g>
    </svg>
</div>

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