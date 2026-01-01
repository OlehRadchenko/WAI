<?php include 'includes/header.php'; ?>
<h2>Zapamiętane zdjęcia</h2>
<form action="index.php?action=cart/clear" method="POST" class="gallery">
    <?php if(empty($images)): ?>
        <p>Brak zapamiętanych zdjęć.</p>
    <?php else: ?>
        <div class="images">
            <?php foreach ($images as $img): ?>
                <?php 
                    $idStr = (string)$img['_id'];
                    $amount = $_SESSION['cart'][$idStr] ?? 0;
                ?>
                <div class="gallery-item">
                    <img src="../thumbnails/<?= $img['name'] ?>" alt="Thumb">
                    
                    <h3><?= htmlspecialchars($img['title']) ?></h3>
                    <p>Autor: <?= htmlspecialchars($img['author']) ?></p>
                    <?php if(isset($img['access']) && $img['access'] == 'private') echo "<small style='color:red'>Prywatne</small>"; ?>
                    <p><strong>Ilość odbitek: <?= $amount ?></strong></p>
                    
                    <label>
                        <input type="checkbox" name="remove_ids[]" value="<?= $idStr ?>"> 
                        Usuń
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="margin">
            <button type="submit" name="delete">Usuń zaznaczone</button>
        </div>
    <?php endif; ?>
</form>
</body></html>