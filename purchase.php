<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$package_id = $_GET['package'] ?? null;
$message = '';
$error = '';

if ($package_id) {
    $response = supabaseRequest('credit_packages?id=eq.' . $package_id . '&select=*');
    $package = $response['code'] === 200 && !empty($response['data']) ? $response['data'][0] : null;
} else {
    $package = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $package) {
    $user = getUser();
    $payment_method = $_POST['payment_method'] ?? 'demo';

    $transaction_data = [
        'user_id' => $user['id'],
        'package_id' => $package['id'],
        'amount' => $package['price'],
        'credits' => $package['credits'],
        'status' => 'completed',
        'payment_method' => $payment_method,
        'transaction_ref' => 'DEMO-' . strtoupper(uniqid())
    ];

    $transaction_response = supabaseRequest('transactions', 'POST', $transaction_data);

    if ($transaction_response['code'] === 201) {
        $new_credits = $user['credits'] + $package['credits'];

        $update_response = supabaseRequest(
            'users?id=eq.' . $user['id'],
            'PATCH',
            ['credits' => $new_credits]
        );

        if ($update_response['code'] === 200) {
            $message = 'Ödeme başarılı! ' . $package['credits'] . ' kredi hesabınıza eklendi.';
            $_SESSION['credits'] = $new_credits;
        } else {
            $error = 'Krediler güncellenirken bir hata oluştu.';
        }
    } else {
        $error = 'İşlem kaydedilirken bir hata oluştu.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - Ödeme</title>
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

        .container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .package-summary {
            background: rgba(0, 0, 0, 0.2);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .package-summary h2 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: #5eead4;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.5rem;
            font-weight: 700;
            color: #5eead4;
        }

        .payment-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        select, input {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 1rem;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #14b8a6;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
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

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            border: 2px solid #22c55e;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border: 2px solid #ef4444;
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #5eead4;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php" class="logo"><?php echo SERVER_NAME; ?></a>
        <div class="nav-links">
            <a href="index.php">Ana Sayfa</a>
            <a href="store.php">Mağaza</a>
            <a href="dashboard.php">Hesabım</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                    <br><br>
                    <a href="dashboard.php" class="btn btn-primary">Hesabıma Git</a>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!$package): ?>
                <h1>Paket Bulunamadı</h1>
                <p>Seçtiğiniz paket bulunamadı.</p>
                <a href="store.php" class="back-link">← Mağazaya Dön</a>
            <?php elseif (!$message): ?>
                <h1>Ödeme</h1>

                <div class="package-summary">
                    <h2><?php echo htmlspecialchars($package['name']); ?></h2>
                    <div class="summary-row">
                        <span>Kredi Miktarı:</span>
                        <span>💎 <?php echo number_format($package['credits']); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Fiyat:</span>
                        <span>$<?php echo number_format($package['price'], 2); ?> USD</span>
                    </div>
                    <div class="summary-row">
                        <span>Toplam:</span>
                        <span>$<?php echo number_format($package['price'], 2); ?> USD</span>
                    </div>
                </div>

                <form method="POST" class="payment-form">
                    <div class="form-group">
                        <label>Ödeme Yöntemi</label>
                        <select name="payment_method" required>
                            <option value="demo">Demo Ödeme (Test)</option>
                            <option value="credit_card">Kredi Kartı</option>
                            <option value="paypal">PayPal</option>
                            <option value="crypto">Kripto Para</option>
                        </select>
                    </div>

                    <div style="background: rgba(251, 191, 36, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 2px solid #fbbf24;">
                        <strong>Demo Modu:</strong> Bu demo ödeme sistemidir. Gerçek ödeme yapılmayacaktır.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Ödemeyi Tamamla
                    </button>
                </form>

                <a href="store.php" class="back-link">← Mağazaya Dön</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
