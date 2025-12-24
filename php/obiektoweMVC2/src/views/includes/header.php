<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria MVC</title>
    
    <link rel="stylesheet" href="static/css/styles.css">
    
    <script src="static/js/main.js" defer></script>
</head>
<body>
<nav>
    <a href="index.php?action=/">Galeria</a>
    <a href="index.php?action=upload">Dodaj zdjęcie</a>
    <a href="index.php?action=selected">Zapamiętane (<?= count($_SESSION['cart'] ?? []) ?>)</a>
    <a href="index.php?action=search">Szukaj</a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <span style="float:right">Witaj, <?= $_SESSION['user_login'] ?> | <a href="index.php?action=logout">Wyloguj</a></span>
    <?php else: ?>
        <span style="float:right"><a href="index.php?action=login">Logowanie</a> | <a href="index.php?action=register">Rejestracja</a></span>
    <?php endif; ?>
</nav>
<hr>