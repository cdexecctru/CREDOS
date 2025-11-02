<?php
// db.php - RAILWAY MYSQL Veritabanı Bağlantı Ayarları (INTERNAL Host)

// AuthMe sabitleri
define('AUTHME_TABLE', 'authme'); 
define('HASH_ALGORITHM', 'sha256'); 

// RAILWAY MYSQL İÇ (INTERNAL) BAĞLANTI BİLGİLERİ
$db_host = 'mysql.railway.internal';    // MYSQL_HOST
$db_user = 'root';                      // MYSQL_USER
$db_pass = 'NFtYZSsGxv0OkweUvCCSEXOjdhZarYqh1'; // MYSQL_PASSWORD
$db_name = 'railway';                   // MYSQL_DATABASE
$db_port = '3306';                      // MYSQL_PORT

// Veritabanı Bağlantısını Kurma (PDO Kullanarak)
try {
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false 
    ]);

} catch (PDOException $e) {
    // Hata oluşursa sadece hatayı göster, programı sonlandır
    die("<div style='background:red; color:white; padding:15px; border-radius:5px; text-align:center;'>
        <h2>Kritik Veritabanı Hatası</h2>
        <p>Lütfen Railway ayarlarınızı kontrol edin. Hata Kodu: " . $e->getCode() . "</p>
        </div>");
}
// Bu PHP bloğundan sonra boşluk veya satır olmamalıdır