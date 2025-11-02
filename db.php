<?php
// db.php - RAILWAY MYSQL Veritabanı Bağlantı Ayarları (INTERNAL Host)

// AuthMe tablosu bilgileri
// AuthMe kayıt/giriş işlemleri için gerekli sabitler
define('AUTHME_TABLE', 'authme'); 
define('HASH_ALGORITHM', 'sha256'); 

// 🚀 RAILWAY MYSQL İÇ (INTERNAL) BAĞLANTI BİLGİLERİ
// Web sitesi (Railway) ve veritabanı (Railway) aynı altyapıda olduğu için INTERNAL adres kullanılır.
// Bilgiler, Railway panel ekran görüntülerinden alınmıştır.
$db_host = 'mysql.railway.internal';    // MYSQL_HOST
$db_user = 'root';                      // MYSQL_USER
$db_pass = 'NFtYZSsGxv0OkweUvCCSEXOjdhZarYqh1'; // MYSQL_PASSWORD
$db_name = 'railway';                   // MYSQL_DATABASE
$db_port = '3306';                      // MYSQL_PORT

// -----------------------------------------------------------------------------
// Veritabanı Bağlantısını Kurma (PDO Kullanarak)
// -----------------------------------------------------------------------------

try {
    // Veritabanı bağlantı dizesi
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    
    // PDO sınıfını kullanarak bağlantıyı oluştur
    // Ayrıca veritabanının SSL kullanmamasını da belirtiyoruz (genellikle internal bağlantılar için gereksizdir)
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false // SSL sertifikası doğrulamasını devre dışı bırak
    ]);

} catch (PDOException $e) {
    // Bağlantı başarısız olursa projeyi durdur ve hata mesajı göster
    die("<div style='background:red; color:white; padding:15px; border-radius:5px; text-align:center;'>
        <h2>Veritabanı Bağlantı Hatası (INTERNAL)</h2>
        <p>Lütfen Railway'deki MySQL hizmetinizin aktif olduğunu ve iç host bilgisinin (mysql.railway.internal) doğru olduğunu kontrol edin.</p>
        <p style='font-size: small;'>Hata Detayı: " . $e->getMessage() . "</p>
        </div>");
}

?>