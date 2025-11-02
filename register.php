<?php 
include 'header.php'; 
include 'db.php'; // Ana dizindeki db.php dosyasýný dahil et

$error = '';
$success = '';

// AuthMe'nin kullandýðý formatta hash oluþturma fonksiyonu
// Bu, Minecraft sunucusunun da kullanýcýyý tanýmasý için kritiktir.
function createAuthMeHash($password) {
    // AuthMe'nin SHA256 formatýný taklit ediyoruz: $sha$<salt>$$<hash>
    // Gerçek AuthMe, daha karmaþýk bir salt ve hash mekanizmasý kullanýr. 
    // Basit bir SHA256 hash'i ve rastgele bir salt ile simülasyon yapalým.
    
    // Rastgele bir 16 karakterli salt oluþtur
    $salt = substr(bin2hex(random_bytes(8)), 0, 16); 
    
    // AuthMe'nin istediði formatta þifreyi hazýrlar: password + salt
    $combined = $password . $salt;
    
    // Þifreyi SHA256 ile hashle
    $hashed_password = hash(AUTHME_TABLE, $combined);
    
    // AuthMe'nin tam hash formatýný döndür
    return "\$sha\$$salt\$$hashed_password"; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_check = $_POST['password_check'];

    // Temel kontroller
    if (empty($username) || empty($email) || empty($password) || empty($password_check)) {
        $error = "Tüm alanlarý doldurmak zorunludur.";
    } elseif ($password !== $password_check) {
        $error = "Girdiðiniz þifreler eþleþmiyor.";
    } elseif (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Kullanýcý adý en az 3 karakter olmalý ve sadece harf, rakam ve alt çizgi içerebilir.";
    } elseif (strlen($password) < 6) {
        $error = "Þifre en az 6 karakter olmalýdýr.";
    } else {
        try {
            // 1. Kullanýcý adý benzersizlik kontrolü
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM " . AUTHME_TABLE . " WHERE username = ?");
            $stmt->execute([strtolower($username)]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Bu kullanýcý adý zaten alýnmýþ.";
            } else {
                // 2. E-posta benzersizlik kontrolü (Opsiyonel, AuthMe bunu zorunlu kýlmaz ama web için iyidir)
                // AuthMe tablosunda genellikle e-posta sütunu bulunur (varsa kontrol et)
                $email_check_stmt = $pdo->prepare("SELECT COUNT(*) FROM " . AUTHME_TABLE . " WHERE email = ?");
                $email_check_stmt->execute([$email]);
                if ($email_check_stmt->fetchColumn() > 0) {
                     $error = "Bu e-posta adresi zaten kayýtlý.";
                } else {
                    
                    // 3. AuthMe uyumlu þifre hash'i oluþtur
                    $authme_password_hash = createAuthMeHash($password);
                    
                    // 4. Veritabanýna kaydet
                    $insert_stmt = $pdo->prepare("INSERT INTO " . AUTHME_TABLE . " (username, password, email, registrationdate, ip) 
                                                  VALUES (?, ?, ?, ?, ?)");
                    
                    // Kullanýcý adýný küçük harfle kaydet (AuthMe standartý)
                    $username_lower = strtolower($username);
                    $registration_date = time(); // Unix zaman damgasý
                    $ip_address = $_SERVER['REMOTE_ADDR']; // Kayýt olan kullanýcýnýn IP adresi
                    
                    $insert_stmt->execute([
                        $username_lower,
                        $authme_password_hash,
                        $email,
                        $registration_date,
                        $ip_address
                    ]);

                    $success = "Kayýt baþarýlý! Þimdi <a href='login.php' class='auth-link' style='color: #4CAF50;'>Giriþ Yap</a>abilirsiniz.";
                }
            }

        } catch (PDOException $e) {
            $error = "Kayýt sýrasýnda bir veritabaný hatasý oluþtu. Lütfen tekrar deneyin.";
            // Geliþtirme için: $error .= " Detay: " . $e->getMessage();
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
        
        <?php if (!empty($error)): ?>
            <div style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div style="background-color: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form action="register.php" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="reg-username"><i class="fas fa-user"></i> Kullanýcý Adý</label>
                <input type="text" id="reg-username" name="username" placeholder="Minecraft Kullanýcý Adý" required>
            </div>
            
            <div class="form-group">
                <label for="reg-email"><i class="fas fa-envelope"></i> E-Posta</label>
                <input type="email" id="reg-email" name="email" placeholder="Geçerli E-Posta Adresiniz" required>
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