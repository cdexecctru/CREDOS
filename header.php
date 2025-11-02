<?php
// Oturumu başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// db.php dosyasını dahil et
include_once 'db.php'; 

// Çıkış yap butonu için basit bir logout dosyası gereklidir: logout.php
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
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="#" class="btn btn-login disabled">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <a href="logout.php" class="btn btn-register btn-logout">
                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
            </a>
        <?php else: ?>
            <a href="login.php" class="btn btn-login">Giriş Yap</a>
            <a href="register.php" class="btn btn-register">Kayıt Ol</a>
        <?php endif; ?>
    </div>
</header>
<main class="container">