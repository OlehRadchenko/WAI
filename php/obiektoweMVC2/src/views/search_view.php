<?php include 'includes/header.php'; ?>
<h2>Wyszukiwarka AJAX</h2>
<input type="text" onkeyup="searchImages(this.value)" placeholder="Wpisz tytuł...">
<div id="results">
<?php if(!empty($images)): ?>
    <div class="gallery">
        <div class="images">
            <?php foreach ($images as $img): ?>
                <div class="gallery-item">
                    <a href="../images/<?= $img['name'] ?>" target="_blank">
                        <img src="../thumbnails/<?= $img['name'] ?>" alt="Thumb of <?= $img['name'] ?>">
                    </a>
                    <p>Title: <?= htmlspecialchars($img['title']) ?></p>
                    <p>Author: <?= htmlspecialchars($img['author']) ?></p>
                    <?php if(isset($img['access']) && $img['access'] == 'private') echo "<small style='color:red'>Prywatne</small><br>"; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
<?php else: ?>
    <p>Brak wyników wyszukiwania.</p>
<?php endif; ?>
</div>
</body>
</html>