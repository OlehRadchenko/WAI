<?php include 'includes/header.php'; ?>
<h2>Wyszukiwarka AJAX</h2>
<input type="text" onkeyup="searchImages(this.value)" placeholder="Wpisz tytuł...">
<div id="results">
    <?php
        if(isset($images)) {
            include __DIR__ . '/partial/search_results.php';
        }
    ?>
</div>
</body>
</html>