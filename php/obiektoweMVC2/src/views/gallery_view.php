<?php include 'includes/header.php'; ?>

<h2>Galeria Zdjęć</h2>

<form action="index.php?action=cart/add" method="POST">
    <?php foreach ($images as $img): ?>
        <div class="gallery-item">
            <a href="../images/<?= $img['name'] ?>" target="_blank">
                <img src="../thumbnails/<?= $img['name'] ?>" alt="Thumb">
            </a>
            <p>Title: <?= htmlspecialchars($img['title']) ?></p>
            <p>Author: <?= htmlspecialchars($img['author']) ?></p>
            <?php if(isset($img['access']) && $img['access'] == 'private') echo "<small style='color:red'>Prywatne</small><br>"; ?>
            
            <label>
                <input type="checkbox" name="selected_ids[]" value="<?= $img['_id'] ?>" 
                <?= (in_array((string)$img['_id'], $_SESSION['cart'] ?? [])) ? 'checked' : '' ?>>
                Wybierz
            </label>
        </div>
    <?php endforeach; ?>
    
    <div style="margin-top: 20px;">
        <button type="submit">Zapamiętaj wybrane</button>
    </div>
</form>

<div style="margin-top: 20px;">
    Strona: 
    <?php for($i=1; $i<=$pages; $i++): ?>
        <a href="index.php?action=/&page=<?= $i ?>" style="<?= ($i==$page)?'font-weight:bold':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

</body></html>