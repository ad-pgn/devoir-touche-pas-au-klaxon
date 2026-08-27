<?php
/**
 * Layout principal de l'application.
 *
 * @var string $content Contenu de la page, déjà rendu.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>

<body>
    <?php require __DIR__ . '/header.php'; ?>

    <main class="container">
        <?php require __DIR__ . '/flash.php'; ?>
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/footer.php'; ?>
</body>
</html>