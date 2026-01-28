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
</head>
<body>
<div class="wrapper">
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main>
    <?= $content ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
</div>
</body>
</html>