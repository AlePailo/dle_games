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

<main>
    <?= $content ?>
    <div id="help-container" class="fullscreen-popup-container flex-center hidden">
        <div class="popup-menu">
            <h1>HOW TO PLAY</h1>
            <div>
                <p>Guess the hidden character.</p>
                <p>Each guess will reveal how close you are:</p>
                <p>🟩 Green means the attribute matches.</p>
                <p>🟥 Red means it does not match.</p>
                <p>Use previous guesses to narrow down the possibilities.</p>
            </div>
            <div>
                <h3>EXAMPLE:</h3>
                <div class="example-table-container">
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
                                <td class="table-cell"><img src="<?= $basePath ?>/assets/img/characters_icons/Heiji_Hattori.jpg" alt=""></td>
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
                </div>
                <div class="example-table-container">
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
                                <td class="table-cell"><img src="<?= $basePath ?>/assets/img/characters_icons/Conan_Edogawa.jpg" alt=""></td>
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
                </div>
            </div>
            <button id="close-help-menu-btn">CLOSE</button>
        </div>
    </div>
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