<h1 class="home__title visually-hidden">Select a franchise to start guessing</h1>
<form class="franchise-search" role="search">
    <label for="franchise-search-input" class="visually-hidden">Search Franchise</label>
    <input type="text" name="franchise-search-input" id="franchise-search-input" placeholder="Search for a franchise..." autocomplete="off">
    <svg aria-hidden="true" viewBox="0 0 50 50">
        <path fill="#000" d="M21,3c-9.398,0-17,7.602-17,17s7.602,17,17,17c3.355,0,6.461-0.984,9.094-2.656l12.281,12.281l4.25-4.25L34.5,27.281C36.68,24.422,38,20.879,38,17C38,7.602,30.398,3,21,3z M21,7c7.199,0,13,5.801,13,13s-5.801,13-13,13s-13-5.801-13-13S13.801,7,21,7z"/>
    </svg>
</form>

<ul class="franchise-grid" role="list" aria-live="polite">

    <?php foreach ($franchisesList as $franchise): 

        $name = htmlspecialchars($franchise->getName());
        $slug = htmlspecialchars($franchise->getSlug());
        $icon = htmlspecialchars($franchise->getIconUrl());

        $isActive = $franchise->getIsActive();
        $isNew = $franchise->isNew();

        $url = $basePath . '/' . $slug;

        $tag = $isActive ? 'a' : 'div';

        $attributes = $isActive
            ? 'href="' . $url . '" aria-label="Play ' . $name . '"'
            : 'aria-disabled="true"';

    ?>

        <li class="franchise-grid__item" data-name="<?= strtolower($name) ?>">

                <<?= $tag ?>
                    class="franchise-card <?= !$isActive ? 'disabled' : '' ?>"
                    <?= $attributes ?>
                >

                    <div class="franchise-card__image-container">

                        <?php if ($isNew): ?>
                            <span class="franchise-card__badge" aria-label="New game">
                                NEW
                            </span>
                        <?php endif; ?>

                        <?php if (!$isActive): ?>
                            <span class="franchise-card__badge" aria-label="Game disabled">
                                DISABLED
                            </span>
                        <?php endif; ?>

                        <img
                            class="franchise-card__image"
                            src="<?= $basePath ?>/assets/img/games_icons/<?= $icon ?>"
                            alt=""
                            loading="lazy"
                        >

                    </div>

                    <h3 class="franchise-card__name">
                        <?= strtoupper($name) ?>
                    </h3>

                </<?= $tag ?>>

        </li>

    <?php endforeach; ?>

</ul>