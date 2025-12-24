<?php include 'includes/header.php'; ?>
<h2>Logowanie</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form action="index.php?action=login" method="POST">
    <input type="text" name="login" placeholder="Login" required><br>
    <input type="password" name="password" placeholder="Hasło" required><br>
    <button type="submit">Zaloguj</button>
</form>
</body></html>