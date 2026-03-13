<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'DLE Games') ?></title>

    <?php if (!empty($metaDescription)): ?>
        <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div class="wrapper" style="background-image: url(assets/img/backgrounds/<?= $gameBackground ?>)">
        <?php require __DIR__ . '/../partials/navbar.php'; ?>

        <main class="flex-center fd-column">
            <?= $content ?>
            <aside id="help-container" class="fullscreen-popup-container flex-center hidden" role="dialog" aria-modal="true" aria-labelledby="help-title">
                <div class="popup-menu">
                    <header>
                        <h2 id="help-title">HOW TO PLAY</h2>
                    </header>
                    <section>
                        <p>Guess the hidden character.</p>
                        <p>Each guess will reveal how close you are:</p>
                        <ul>
                            <li>🟩 Green means the attribute matches.</li>
                            <li>🟥 Red means it does not match.</li>
                        </ul>
                    </section>
                    <section aria-labelledby="example-title">
                        <h3 id="example-title">EXAMPLE:</h3>
                        <figure class="example-table-container">
                            <table class="example-table">
                                <thead>
                                    <tr>
                                        <th>IMAGE</th>
                                        <th>NAME</th>
                                        <th>GENDER</th>
                                        <th>AGE</th>
                                        <th>HAIR COLOR</th>
                                        <th>EYES COLOR</th>
                                        <th>AFFILIATION</th>
                                        <th>ROLE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="table-cell"><img src="<?= $basePath ?>/assets/img/characters_icons/detective-conan/Heiji_Hattori.jpg" alt=""></td>
                                        <td class="table-cell wrong">Heiji Hattori</td>
                                        <td class="table-cell correct">Male</td>
                                        <td class="table-cell wrong">Teen</td>
                                        <td class="table-cell correct">Brown</td>
                                        <td class="table-cell correct">Blue</td>
                                        <td class="table-cell wrong">Kaiho High School</td>
                                        <td class="table-cell correct">Detective</td>
                                        <td class="table-cell correct">Alive</td>
                                    </tr>
                                </tbody>
                            </table>
                        </figure>
                        <figure class="example-table-container">
                            <table class="example-table">
                                <thead>
                                    <tr>
                                        <th>IMAGE</th>
                                        <th>NAME</th>
                                        <th>GENDER</th>
                                        <th>AGE</th>
                                        <th>HAIR COLOR</th>
                                        <th>EYES COLOR</th>
                                        <th>AFFILIATION</th>
                                        <th>ROLE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="table-cell"><img src="<?= $basePath ?>/assets/img/characters_icons/detective-conan/Conan_Edogawa.jpg" alt=""></td>
                                        <td class="table-cell correct">Conan Edogawa</td>
                                        <td class="table-cell correct">Male</td>
                                        <td class="table-cell correct">Child</td>
                                        <td class="table-cell correct">Brown</td>
                                        <td class="table-cell correct">Blue</td>
                                        <td class="table-cell correct">Teitan Elementary</td>
                                        <td class="table-cell correct">Detective</td>
                                        <td class="table-cell correct">Alive</td>
                                    </tr>
                                </tbody>
                            </table>
                        </figure>
                    </section>
                    <footer>
                        <button id="close-help-menu-btn" class="popup-menu-btn" aria-label="Close help menu">GOT IT !</button>
                    </footer>
                </div>
            </aside>
        </main>

        <?php require __DIR__ . '/../partials/footer.php'; ?>
    </div>
</body>
<?php 
    echo '<script src="assets/js/helpMenu.js"></script>';
    if($pageType === 'Game') {
        echo '<script src="assets/js/game.js"></script>';
    } elseif($pageType === 'Home') {
        echo '<script src="assets/js/home.js"></script>';
    }
?>
</html>