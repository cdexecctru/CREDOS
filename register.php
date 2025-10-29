<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Tüm alanları doldurunuz.';
    } elseif ($password !== $confirm_password) {
        $error = 'Şifreler eşleşmiyor.';
    } elseif (strlen($password) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } else {
        $check_username = supabaseRequest('users?username=eq.' . urlencode($username) . '&select=id');
        if (!empty($check_username['data'])) {
            $error = 'Bu kullanıcı adı zaten kullanılıyor.';
        } else {
            $check_email = supabaseRequest('users?email=eq.' . urlencode($email) . '&select=id');
            if (!empty($check_email['data'])) {
                $error = 'Bu e-posta adresi zaten kullanılıyor.';
            } else {
                $user_data = [
                    'username' => $username,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'credits' => 0
                ];

                $response = supabaseRequest('users', 'POST', $user_data);

                if ($response['code'] === 201 && !empty($response['data'])) {
                    $success = 'Kayıt başarılı! Giriş yapabilirsiniz.';
                } else {
                    $error = 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - Kayıt Ol</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #5eead4;
        }

        h1 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #14b8a6;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
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
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border: 2px solid #ef4444;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            border: 2px solid #22c55e;
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .auth-footer a {
            color: #5eead4;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            opacity: 0.8;
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-home a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="logo"><?php echo SERVER_NAME; ?></div>
            <h1>Kayıt Ol</h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Kullanıcı Adı</label>
                        <input type="text" name="username" placeholder="MinecraftUsername" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>E-posta</label>
                        <input type="email" name="email" placeholder="email@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label>Şifre Tekrar</label>
                        <input type="password" name="confirm_password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                </form>
            <?php endif; ?>

            <div class="auth-footer">
                Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a>
            </div>

            <div class="back-home">
                <a href="index.php">← Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
</body>
</html>
