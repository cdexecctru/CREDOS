<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getUser();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$transactions_response = supabaseRequest('transactions?user_id=eq.' . $user['id'] . '&select=*&order=created_at.desc&limit=10');
$transactions = $transactions_response['code'] === 200 ? $transactions_response['data'] : [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - Hesabım</title>
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

        .welcome-section {
            margin-bottom: 3rem;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            opacity: 0.8;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 16px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #5eead4;
        }

        .section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .section h2 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .transactions-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .transactions-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-completed {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .transactions-table {
                font-size: 0.9rem;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 0.75rem 0.5rem;
            }

            .welcome-section h1 {
                font-size: 2rem;
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
            <a href="dashboard.php">Hesabım</a>
            <a href="logout.php" class="btn btn-primary">Çıkış Yap</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <h1>Hoş Geldin, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p>Hesap bilgilerinizi ve işlemlerinizi buradan yönetebilirsiniz.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Toplam Kredi</div>
                <div class="stat-value">💎 <?php echo number_format($user['credits']); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Toplam İşlem</div>
                <div class="stat-value"><?php echo count($transactions); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Üyelik Tarihi</div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    <?php echo date('d.m.Y', strtotime($user['created_at'])); ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Son İşlemler</h2>

            <?php if (empty($transactions)): ?>
                <div class="empty-state">
                    <p>Henüz işlem geçmişiniz bulunmamaktadır.</p>
                    <br>
                    <a href="store.php" class="btn btn-primary">Kredi Satın Al</a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="transactions-table">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Kredi</th>
                                <th>Tutar</th>
                                <th>Durum</th>
                                <th>Referans</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo date('d.m.Y H:i', strtotime($transaction['created_at'])); ?></td>
                                    <td>💎 <?php echo number_format($transaction['credits']); ?></td>
                                    <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                            <?php
                                            $status_text = [
                                                'completed' => 'Tamamlandı',
                                                'pending' => 'Beklemede',
                                                'failed' => 'Başarısız'
                                            ];
                                            echo $status_text[$transaction['status']] ?? $transaction['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td style="font-family: monospace; font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($transaction['transaction_ref']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Hesap Bilgileri</h2>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <div class="stat-label">Kullanıcı Adı</div>
                    <div style="font-size: 1.2rem; font-weight: 600;"><?php echo htmlspecialchars($user['username']); ?></div>
                </div>
                <div>
                    <div class="stat-label">E-posta</div>
                    <div style="font-size: 1.2rem; font-weight: 600;"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div>
                    <div class="stat-label">Son Giriş</div>
                    <div style="font-size: 1.2rem; font-weight: 600;">
                        <?php echo date('d.m.Y H:i', strtotime($user['last_login'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
