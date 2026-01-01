<?php include 'includes/header.php'; ?>

<h2>Galeria Zdjęć</h2>
<?php if(!empty($images)): ?>
<form action="index.php?action=cart/add" method="POST" class="gallery">
    <div class="images">
        <?php foreach ($images as $img): ?>
            <div class="gallery-item">
                <a href="../images/<?= $img['name'] ?>" target="_blank">
                    <img src="../thumbnails/<?= $img['name'] ?>" alt="Thumb of <?= $img['name'] ?>">
                </a>
                <p>Title: <?= htmlspecialchars($img['title']) ?></p>
                <p>Author: <?= htmlspecialchars($img['author']) ?></p>
                <?php if(isset($img['access']) && $img['access'] == 'private') echo "<small style='color:red'>Prywatne</small>"; ?>
                <label>Wpisz ilość odbitek: </label>
                <input type="number" name="amounts[<?= $img['_id'] ?>]" value="<?php echo $_SESSION['cart'][(string)$img['_id']] ?? 1; ?>" min="1" style="width: 60px;"><br>
                <label>
                    <input type="checkbox" name="selected_ids[]" value="<?= $img['_id'] ?>" 
                    <?= (array_key_exists((string)$img['_id'], $_SESSION['cart'] ?? [])) ? 'checked' : '' ?>>
                    Wybierz
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="margin">
        <button type="submit">Zapamiętaj wybrane</button>
    </div>
</form>

<div class="margin">
    Strona: 
    <?php for($i=1; $i<=$pages; $i++): ?>
        <a href="index.php?action=/&page=<?= $i ?>" style="<?= ($i==$page)?'font-weight:bold':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php else: ?>
    <p>Brak zdjęć do wyświetlenia.</p>
<?php endif; ?>
</body>
</html>