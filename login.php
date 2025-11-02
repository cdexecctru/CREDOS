<?php 
include 'header.php'; 

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php"); 
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtolower(trim($_POST['username'])); 
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $current_time = time(); 

    if (empty($username) || empty($password)) {
        $error = "Lütfen kullanýcý adý ve þifrenizi girin.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT username, password, email FROM " . AUTHME_TABLE . " WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user) {
                $authme_hash = $user['password'];
                $is_valid = false;

                // AuthMe SHA256 Kontrolü
                if (strpos($authme_hash, '$SHA$') === 0) {
                    $db_hash = substr($authme_hash, 5); 
                    $input_hash = hash(HASH_ALGORITHM, $password); 

                    if ($db_hash === $input_hash) {
                        $is_valid = true;
                    }
                } 
                
                if ($is_valid) {
                    // Veritabaný alanlarýný güncelle (giriþ yapýldý)
                    $update_stmt = $pdo->prepare("UPDATE " . AUTHME_TABLE . " SET isLogged = 1, lastlogin = :time, ip = :ip WHERE username = :username");
                    $update_stmt->execute([
                        'time' => $current_time * 1000, 
                        'ip' => $ip_address,
                        'username' => $username
                    ]);

                    // Oturum baþlat
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];

                    header("Location: index.php?success=login"); 
                    exit();
                } else {
                    $error = "Hatalý þifre girdiniz.";
                }
            } else {
                $error = "Kullanýcý adý bulunamadý.";
            }

        } catch (PDOException $e) {
            $error = "Veritabaný hatasý oluþtu: Giriþ yapýlamadý.";
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
        
        <?php if ($error): ?>
            <div style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="#" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Kullanýcý Adý</label>
                <input type="text" id="username" name="username" placeholder="Kullanýcý Adýnýz" 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
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