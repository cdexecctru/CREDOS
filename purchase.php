<?php include 'header.php'; 
// Satın alınacak öğeyi URL'den al (Şimdilik sadece örnek amaçlı)
$item_name = isset($_GET['item']) ? htmlspecialchars($_GET['item']) : 'Bilinmeyen Ürün';
$item_price = 'Bilinmiyor';

if ($item_name == 'ultra-vip') {
    $display_name = 'ULTRA VIP Paketi';
    $item_price = '150 ₺';
} elseif ($item_name == 'efsane-anahtar') {
    $display_name = 'Efsane Kasa Anahtarı';
    $item_price = '50 ₺';
} elseif ($item_name == 'para-paketi') {
    $display_name = 'Büyük Para Paketi';
    $item_price = '30 ₺';
} else {
    $display_name = 'Bilinmeyen Ürün';
}

// Form Gönderimi Kontrolü (Sadece Havale/EFT seçeneği için)
$show_transfer_info = false;
$payment_method = isset($_POST['payment']) ? $_POST['payment'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $payment_method == 'transfer') {
    $show_transfer_info = true;
}

?>

<h1 class="page-title"><i class="fas fa-shopping-cart"></i> Satın Alma İşlemini Tamamla</h1>

<div class="purchase-layout">
    
    <div class="card purchase-details">
        <h2>Ürün Bilgileri</h2>
        <div class="detail-row">
            <strong>Ürün Adı:</strong> <span><?php echo $display_name; ?></span>
        </div>
        <div class="detail-row">
            <strong>Fiyat:</strong> <span class="price-highlight"><?php echo $item_price; ?></span>
        </div>
        <div class="detail-row">
            <strong>Alıcı (Giriş Yapılan Kullanıcı):</strong> <span>Örnek_Oyuncu_Adı</span>
        </div>
        <hr>
        <p class="warning-text"><i class="fas fa-info-circle"></i> Satın alma işlemi geri iadesizdir. Kullanıcı adınızın doğru olduğundan emin olun.</p>
    </div>

    <div class="card payment-method">
        
        <?php if ($show_transfer_info): // Havale/EFT seçilip form gönderildiyse ?>
            
            <h2>✅ Havale/EFT Bilgileri</h2>
            <p class="page-description" style="margin-bottom: 20px;">Ödemeyi aşağıdaki hesaba yapınız ve Discord üzerinden bildirim yapmayı unutmayınız.</p>
            
            <div class="detail-row" style="background-color: #3d3f57; padding: 15px; border-radius: 5px;">
                <strong>Hesap Sahibi:</strong> <span class="price-highlight">Gamze Özçelik</span>
            </div>
            
            <div class="detail-row" style="background-color: #3d3f57; padding: 15px; border-radius: 5px; margin-top: 10px;">
                <strong>IBAN No:</strong> <span class="price-highlight">Yok (Lütfen Discorddan Sorunuz)</span>
            </div>
            
            <hr>
            <p class="warning-text" style="color: #4CAF50;">Ödeme tamamlandıktan sonra yöneticilere ulaşın.</p>
            <a href="market.php" class="btn btn-primary btn-full btn-lg-confirm" style="margin-top: 20px;">Mağazaya Dön</a>

        <?php else: // Ödeme yöntemi seçimi ekranı ?>
            
            <h2>Ödeme Yöntemi Seç</h2>
            
            <form action="purchase.php?item=<?php echo htmlspecialchars($item_name); ?>" method="POST">
                <div class="form-group payment-group">
                    <input type="radio" id="pay-gpay" name="payment" value="gpay" checked>
                    <label for="pay-gpay"><i class="fas fa-credit-card"></i> Kredi Kartı (Ödeme Sistemi)</label>
                </div>
                
                <div class="form-group payment-group">
                    <input type="radio" id="pay-transfer" name="payment" value="transfer">
                    <label for="pay-transfer"><i class="fas fa-university"></i> **Havale/EFT (Manuel Onay)**</label>
                </div>
                
                <div class="form-group payment-group">
                    <input type="radio" id="pay-mobil" name="payment" value="mobil">
                    <label for="pay-mobile"><i class="fas fa-mobile-alt"></i> Mobil Ödeme (SMS ile)</label>
                </div>
                
                <hr>
                <button type="submit" class="btn btn-register btn-full btn-lg-confirm">Devam Et ve Siparişi Onayla</button>
            </form>

        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>