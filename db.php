<?php
// db.php - RAILWAY MYSQL Veritabanı Bağlantı Ayarları (PUBLIC Host ile)

// AuthMe tablosu bilgileri
// AuthMe kayıt/giriş işlemleri için gerekli sabitler
define('AUTHME_TABLE', 'authme'); 
define('HASH_ALGORITHM', 'sha256'); 

// 🚨 RAILWAY MYSQL HALKA AÇIK (PUBLIC) BAĞLANTI BİLGİLERİ
// Web sitesi ve veritabanı aynı altyapıda olsa da, sizin talebiniz üzerine Public Host kullanıldı.
// Bilgiler, gönderdiğiniz Railway paneli ekran görüntülerinden alınmıştır.
$db_host = 'nozomi.proxy.rly.net';      // MYSQL_PUBLIC_URL Host Kısmı
$db_user = 'root';                      // MYSQL_USER
$db_pass = 'NFtYZSsGxv0OkweUvCCSEXOjdhZarYqh1'; // MYSQL_PASSWORD
$db_name = 'railway';                   // MYSQL_DATABASE
$db_port = '37643';                     // MYSQL_PUBLIC_URL Port Kısmı

// -----------------------------------------------------------------------------
// Veritabanı Bağlantısını Kurma (PDO Kullanarak)
// -----------------------------------------------------------------------------

try {
    // Veritabanı bağlantı dizesi
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    
    // PDO sınıfını kullanarak bağlantıyı oluştur
    $pdo = new PDO($dsn, $db_user, $db_pass);
    
    // Hata modunu ayarlama 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Bağlantı başarısız olursa projeyi durdur ve hata mesajı göster
    die("<div style='background:red; color:white; padding:15px; border-radius:5px; text-align:center;'>
        <h2>Veritabanı Bağlantı Hatası</h2>
        <p>Halka açık host adresi ile bağlantı kurulamadı. Lütfen host ve portun doğru olduğundan emin olun.</p>
        <p style='font-size: small;'>Hata Detayı: " . $e->getMessage() . "</p>
        </div>");
}

?>