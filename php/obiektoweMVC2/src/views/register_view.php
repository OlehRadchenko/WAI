<?php include 'includes/header.php'; ?>
<h2>Rejestracja</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form action="index.php?action=register" method="POST" enctype="multipart/form-data">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="login" placeholder="Login" required><br>
    <input type="password" name="password" placeholder="Hasło" required><br>
    <input type="password" name="repeat_password" placeholder="Powtórz hasło" required><br>

    <label>Zdjęcie profilowe (JPG/PNG):</label><br>
    <input type="file" name="profile_image" required><br><br>
    
    <button type="submit">Zarejestruj</button>
</form>
</body></html>