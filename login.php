<?php 
// 1. Veritabaný baðlantýsýný ana dizinden dahil et
include 'header.php'; 
include 'db.php'; // Klasör kaldýrýldý

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Kullanýcý adý ve þifre boþ býrakýlamaz.";
    } else {
        // 2. Veritabanýndan kullanýcýyý bul
        try {
            // AuthMe genellikle kullanýcý adýný küçük harfe çevirip kaydeder.
            $stmt = $pdo->prepare("SELECT password, salt FROM " . AUTHME_TABLE . " WHERE username = ? LIMIT 1");
            $stmt->execute([strtolower($username)]); 
            $user_data = $stmt->fetch();

            if ($user_data) {
                // 3. Þifre doðrulamasýný yap
                $stored_hash = $user_data['password'];
                $hashed_input = hash(HASH_ALGORITHM, $password); 
                
                // AuthMe'nin karmaþýk hash yapýsýný basitleþtirilmiþ kontrol
                // NOT: Gerçek AuthMe doðrulamasý için özel bir kütüphane gerekir. 
                // Þimdilik sadece basit SHA256 (veya eski AuthMe formatý) kontrolünü yapýyoruz.
                
                if (str_contains($stored_hash, '$sha$')) {
                    // Modern AuthMe için bir doðrulama fonksiyonu simülasyonu
                    // (Gerçek projede güvenli bir kütüphane kullanýlmalý)
                    if (strpos($stored_hash, $hashed_input) !== false) {
                        header("Location: index.php?success=login"); 
                        exit();
                    } else {
                        $error = "Hatalý þifre girdiniz.";
                    }
                } elseif ($hashed_input === $stored_hash) {
                    // Basit SHA256 veya MD5 karþýlaþtýrmasý (Eski sürümler)
                    header("Location: index.php?success=login"); 
                    exit();
                } else {
                    $error = "Hatalý þifre girdiniz.";
                }

            } else {
                $error = "Bu kullanýcý adý veritabanýnda bulunamadý.";
            }

        } catch (PDOException $e) {
            $error = "Bir veritabaný hatasý oluþtu. Lütfen tekrar deneyin.";
            // Gerçek projede hatayý logla
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-icon"><i class="fas fa-user-circle"></i></div> 
            <h2>Giriþ Yap</h2>
            <p>Hesabýnýza eriþmek için bilgilerinizi girin.</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Kullanýcý Adý</label>
                <input type="text" id="username" name="username" placeholder="Kullanýcý Adýnýz" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Þifre</label>
                <input type="password" id="password" name="password" placeholder="Þifreniz" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Giriþ Yap</button>
        </form>
        
        <div class="auth-footer">
            <p>Hesabýnýz yok mu? <a href="register.php" class="auth-link">Kayýt Ol</a></p>
            <p><a href="#" class="auth-link">Þifremi Unuttum</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>