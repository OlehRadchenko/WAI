<?php include 'includes/header.php'; ?>
<h2>Dodaj zdjęcie</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form action="upload" method="POST" enctype="multipart/form-data">
    <input type="file" name="image" required><br><br>
    <input type="text" name="title" placeholder="Tytuł" required><br><br>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <p>Autor: <strong><?= $_SESSION['user_login'] ?></strong></p>
        <label><input type="radio" name="access" value="public" checked> Publiczne</label>
        <label><input type="radio" name="access" value="private"> Prywatne</label><br><br>
    <?php else: ?>
        <input type="text" name="author" placeholder="Autor" required><br><br>
    <?php endif; ?>

    <button type="submit">Wyślij</button>
</form>
</body></html>