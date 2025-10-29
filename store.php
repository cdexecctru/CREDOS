<?php
require_once 'config.php';

$response = supabaseRequest('credit_packages?active=eq.true&select=*&order=price.asc');
$packages = $response['code'] === 200 ? $response['data'] : [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - Kredi Mağazası</title>
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
            display: inline-block;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .package-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .package-card:hover {
            transform: translateY(-10px);
            border-color: #14b8a6;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .package-card.popular {
            border-color: #fbbf24;
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.1));
        }

        .popular-badge {
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #000;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .package-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .package-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: #5eead4;
            margin: 1rem 0;
        }

        .package-price span {
            font-size: 1rem;
            opacity: 0.8;
        }

        .package-credits {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .package-description {
            opacity: 0.8;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
            margin: 1.5rem 0;
        }

        .features-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .features-list li:before {
            content: "✓";
            color: #14b8a6;
            font-weight: 700;
            font-size: 1.2rem;
        }

        footer {
            background: rgba(0, 0, 0, 0.4);
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .packages-grid {
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

    <div class="container">
        <div class="page-header">
            <h1>Kredi Paketleri</h1>
            <p>Sunucumuzda özel içeriklere erişmek için kredi satın alın</p>
        </div>

        <div class="packages-grid">
            <?php foreach ($packages as $package): ?>
                <?php
                $features = json_decode($package['features'], true);
                ?>
                <div class="package-card <?php echo $package['popular'] ? 'popular' : ''; ?>">
                    <?php if ($package['popular']): ?>
                        <div class="popular-badge">EN POPÜLER</div>
                    <?php endif; ?>

                    <div class="package-name"><?php echo htmlspecialchars($package['name']); ?></div>

                    <div class="package-price">
                        $<?php echo number_format($package['price'], 2); ?>
                        <span>USD</span>
                    </div>

                    <div class="package-credits">
                        💎 <?php echo number_format($package['credits']); ?> Kredi
                    </div>

                    <div class="package-description">
                        <?php echo htmlspecialchars($package['description']); ?>
                    </div>

                    <?php if (!empty($features)): ?>
                        <ul class="features-list">
                            <?php foreach ($features as $feature): ?>
                                <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (isLoggedIn()): ?>
                        <a href="purchase.php?package=<?php echo $package['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">
                            Satın Al
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary" style="width: 100%; text-align: center;">
                            Giriş Yapın
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 <?php echo SERVER_NAME; ?>. Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
