<?php include 'includes/header.php'; ?>
<h2>Zapamiętane zdjęcia</h2>
<form action="index.php?action=cart/clear" method="POST">
    <?php if(empty($images)): ?>
        <p>Brak zapamiętanych zdjęć.</p>
    <?php else: ?>
        <?php foreach ($images as $img): ?>
            <div class="gallery-item">
                <img src="../thumbnails/<?= $img['name'] ?>">
                <p><?= htmlspecialchars($img['title']) ?></p>
                <label><input type="checkbox" name="remove_ids[]" value="<?= $img['_id'] ?>"> Usuń</label>
            </div>
        <?php endforeach; ?>
        <br>
        <button type="submit">Usuń zaznaczone z zapamiętanych</button>
    <?php endif; ?>
</form>
</body></html>