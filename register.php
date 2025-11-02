<?php 
include 'header.php'; 

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php"); 
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtolower(trim($_POST['username']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_check = $_POST['password_check'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $regdate = time() * 1000; 
    
    // Doðrulamalar... (Ayný kaldý)
    if (empty($username) || empty($email) || empty($password) || empty($password_check)) {
        $error = "Lütfen tüm alanlarý doldurun.";
    } elseif (!isset($_POST['rules'])) {
        $error = "Kurallarý kabul etmelisiniz.";
    } elseif ($password !== $password_check) {
        $error = "Þifreler eþleþmiyor.";
    } elseif (strlen($username) < 3 || strlen($username) > 16) { 
        $error = "Kullanýcý adý 3-16 karakter olmalýdýr.";
    } elseif (strlen($password) < 8) { 
        $error = "Þifreniz en az 8 karakter olmalýdýr.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin.";
    } else {
        try {
            // Kullanýcý adý ve e-posta çakýþmasý kontrolü
            $stmt = $pdo->prepare("SELECT username, email FROM " . AUTHME_TABLE . " WHERE username = :username OR email = :email LIMIT 1");
            $stmt->execute(['username' => $username, 'email' => $email]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error = ($existing_user['username'] == $username) ? "Bu kullanýcý adý zaten kayýtlýdýr." : "Bu e-posta adresi zaten kayýtlýdýr.";
            } else {
                
                // Þifreyi AuthMe uyumlu SHA256 formatýna çevir
                $hashed_password = hash(HASH_ALGORITHM, $password); 
                $authme_hash = '$SHA$' . $hashed_password;

                // Veritabanýna kaydý ekle
                $stmt = $pdo->prepare("INSERT INTO " . AUTHME_TABLE . " (username, password, email, regdate, regip, lastlogin) 
                                       VALUES (:username, :password, :email, :regdate, :regip, 0)");
                
                $result = $stmt->execute([
                    'username' => $username,
                    'password' => $authme_hash,
                    'email' => $email,
                    'regdate' => $regdate,
                    'regip' => $ip_address
                ]);

                if ($result) {
                    // Kayýt baþarýlý, otomatik giriþ yap
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    
                    header("Location: index.php?success=register"); 
                    exit();
                } else {
                    $error = "Kayýt iþlemi baþarýsýz oldu.";
                }
            }

        } catch (PDOException $e) {
            $error = "Veritabaný hatasý oluþtu: Kayýt yapýlamadý.";
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
            <h2>Kayýt Ol</h2>
            <p>Sunucumuza katýlmak için hesap oluþturun.</p>
        </div>
        
        <?php if ($error): ?>
            <div style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="#" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="reg-username"><i class="fas fa-user"></i> Kullanýcý Adý</label>
                <input type="text" id="reg-username" name="username" placeholder="Minecraft Kullanýcý Adý" 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="reg-email"><i class="fas fa-envelope"></i> E-Posta</label>
                <input type="email" id="reg-email" name="email" placeholder="Geçerli E-Posta Adresiniz" 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="reg-password"><i class="fas fa-lock"></i> Þifre</label>
                <input type="password" id="reg-password" name="password" placeholder="Þifre" required>
            </div>
            
            <div class="form-group">
                <label for="reg-password-check"><i class="fas fa-lock"></i> Þifre (Tekrar)</label>
                <input type="password" id="reg-password-check" name="password_check" placeholder="Þifrenizi Tekrar Girin" required>
            </div>

            <div class="form-check">
                <input type="checkbox" id="rules" name="rules" required>
                <label for="rules">Kurallarý okudum ve kabul ediyorum.</label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Kayýt Ol</button>
        </form>
        
        <div class="auth-footer">
            <p>Hesabýnýz var mý? <a href="login.php" class="auth-link">Giriþ Yap</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>