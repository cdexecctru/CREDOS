<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - Ana Sayfa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            color: #fff;
            min-height: 100vh;
        }

        nav {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #14b8a6;
            color: #fff;
        }

        .btn-primary:hover {
            background: #0d9488;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(20, 184, 166, 0.3);
        }

        .hero {
            text-align: center;
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #fff, #5eead4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .server-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .server-ip {
            font-size: 1.5rem;
            font-weight: 700;
            color: #5eead4;
        }

        .features {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 16px;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            opacity: 0.8;
            line-height: 1.6;
        }

        .discord-banner {
            background: linear-gradient(135deg, #5865F2, #4752C4);
            padding: 3rem 2rem;
            text-align: center;
            margin: 4rem 0;
        }

        .discord-banner h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        footer {
            background: rgba(0, 0, 0, 0.4);
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php" class="logo"><?php echo SERVER_NAME; ?></a>
        <div class="nav-links">
            <a href="index.php">Ana Sayfa</a>
            <a href="store.php">Mağaza</a>
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php">Hesabım</a>
                <a href="logout.php" class="btn btn-primary">Çıkış Yap</a>
            <?php else: ?>
                <a href="login.php">Giriş Yap</a>
                <a href="register.php" class="btn btn-primary">Kayıt Ol</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero">
        <h1><?php echo SERVER_NAME; ?></h1>
        <p>En iyi Minecraft deneyimi için hemen sunucumuza katıl! Özel modlar, özel etkinlikler ve harika bir topluluk seni bekliyor.</p>

        <div class="server-info">
            <div>
                <div style="opacity: 0.8; font-size: 0.9rem; margin-bottom: 0.25rem;">Sunucu IP</div>
                <div class="server-ip"><?php echo SERVER_IP; ?></div>
            </div>
        </div>

        <a href="store.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">Kredi Yükle</a>
    </div>

    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">🎮</div>
            <h3>Özel Oyun Modları</h3>
            <p>Survival, Creative, SkyBlock ve daha fazlası! Her oyuncu için özel modlar.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>7/24 Aktif</h3>
            <p>Güçlü sunucularımız sayesinde kesintisiz oyun deneyimi.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Güvenli Ortam</h3>
            <p>Deneyimli yetkili kadromuz ile güvenli ve adil bir oyun ortamı.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🎁</div>
            <h3>Ödüller</h3>
            <p>Düzenli etkinlikler ve ödüller ile oyun deneyiminizi geliştirin.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">💎</div>
            <h3>Özel Özellikler</h3>
            <p>VIP ve MVP paketleri ile özel komutlar, kozmetikler ve ayrıcalıklara sahip olun.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🤝</div>
            <h3>Aktif Topluluk</h3>
            <p>Discord sunucumuzda binlerce oyuncu ile tanış ve arkadaşlıklar kur.</p>
        </div>
    </div>

    <div class="discord-banner">
        <h2>Discord Topluluğumuza Katıl</h2>
        <p style="margin-bottom: 1.5rem; font-size: 1.1rem;">Etkinliklerden haberdar ol, destek al ve yeni arkadaşlar edin!</p>
        <a href="<?php echo DISCORD_URL; ?>" target="_blank" class="btn" style="background: #fff; color: #5865F2;">Discord'a Katıl</a>
    </div>

    <footer>
        <p>&copy; 2025 <?php echo SERVER_NAME; ?>. Tüm hakları saklıdır.</p>
        <p style="margin-top: 0.5rem; opacity: 0.7;">Minecraft ve Mojang Studios, Microsoft Corporation'ın ticari markalarıdır.</p>
    </footer>
</body>
</html>
