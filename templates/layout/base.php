<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
    <nav>
        <a href="/salles">Salles</a>
        <a href="/reservations">Reservations</a>
    </nav>
</header>
<main class="container">
    <?= $content ?>
</main>
</body>
</html>
