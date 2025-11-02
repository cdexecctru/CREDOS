<?php
// Bu kısım, ileride kullanıcı oturumları için kullanılacak.
// session_start(); 
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evurt | BoxPvP Minecraft Sitesi</title> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<header class="navbar">
    <div class="logo">EVURT CRAFT</div> 
    <nav class="menu">
        <a href="index.php"><i class="fas fa-home"></i> Anasayfa</a>
        <a href="market.php"><i class="fas fa-store"></i> Mağaza</a>
        <a href="#"><i class="fas fa-comments"></i> Forum</a>
        <a href="#"><i class="fas fa-headset"></i> Destek</a>
    </nav>
    <div class="auth-buttons">
        <a href="login.php" class="btn btn-login">Giriş Yap</a>
        <a href="register.php" class="btn btn-register">Kayıt Ol</a>
    </div>
</header>
<main class="container">

</main>
<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> EVURT CRAFT Projesi. Tum Haklari Saklidir. | Powered by CREDOS Discord:credos_.</p>
</footer>
</body>
</html>
