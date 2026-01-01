<?php
$totalAmount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $totalAmount = array_sum($_SESSION['cart']);
}
?>
<a href="selected">Zapamiętane (<?= $totalAmount ?>)</a>