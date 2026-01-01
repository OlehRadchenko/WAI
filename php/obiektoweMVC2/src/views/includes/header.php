<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria MVC</title>
    
    <link rel="stylesheet" href="static/css/style.css">
    
    <script src="static/js/main.js" defer></script>
</head>
<body>
<nav>
    <a href="index.php?action=/">Galeria</a>
    <a href="index.php?action=upload">Dodaj zdjęcie</a>
    <?php include __DIR__ . '/../partial/cart_stats.php'; ?>
    <a href="index.php?action=search">Szukaj</a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-panel">
            <span>Witaj, <strong><?= htmlspecialchars($_SESSION['user_login']) ?></strong></span>
            
            <img src="<?= $_SESSION['profile_photo_path'] ?>" alt="Zdjęcie profilowe użytkownika <?= $_SESSION['user_login'] ?>" class="user-avatar"/>
            
            <a href="index.php?action=logout" style="margin-left: 5px;">Wyloguj</a>
        </div>
    <?php else: ?>
        <div class="user-panel">
            <a href="index.php?action=login">Logowanie</a> | 
            <a href="index.php?action=register">Rejestracja</a>
        </div>
    <?php endif; ?>
</nav>
<hr>