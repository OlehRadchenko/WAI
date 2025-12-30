<?php include 'includes/header.php'; ?>
<h2>Zapamiętane zdjęcia</h2>
<form action="index.php?action=cart/clear" method="POST" class="gallery">
    <?php if(empty($images)): ?>
        <p>Brak zapamiętanych zdjęć.</p>
    <?php else: ?>
        <div class="images">
            <?php foreach ($images as $img): ?>
                <div class="gallery-item">
                    <img src="../thumbnails/<?= $img['name'] ?>" alt="Thumb of <?= $img['name'] ?>">
                    <h2><?= htmlspecialchars($img['title']) ?></h2>
                    <p><?= htmlspecialchars($img['author']) ?></p>
                    <label><input type="checkbox" name="remove_ids[]" value="<?= $img['_id'] ?>"> Usuń</label>
                </div>
            <?php endforeach; ?>
            <br>
        </div>
        <div style="margin-top: 20px;">
                <button type="submit">Usuń zaznaczone</button>
        </div>
    <?php endif; ?>
</form>
</body></html>