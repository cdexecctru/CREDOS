<?php include 'header.php'; 

// Item detaylarını URL'den al
$item_name = isset($_GET['item']) ? htmlspecialchars($_GET['item']) : 'Bilinmeyen Ürün';
$item_price = 'Bilinmiyor';

// Ürün bilgileri
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

// Oturumdan kullanıcı adını çekme
$buyer_username = "Giriş Yapılmadı";
$is_logged_in = false;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $buyer_username = htmlspecialchars($_SESSION['username']);
    $is_logged_in = true;
}

?>

<h1 class="page-title"><i class="fas fa-shopping-cart"></i> Satın Alma İşlemini Tamamla</h1>

<div class="purchase-layout">
    
    <div class="card purchase-details">
        <h2>Ürün Bilgileri</h2>
        <?php if (!$is_logged_in): ?>
            <div style="background-color: #ff9800; color: #333; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold;">
                <i class="fas fa-exclamation-triangle"></i> Satın alma işlemine devam etmek için lütfen giriş yapın.
            </div>
        <?php endif; ?>
        
        <div class="detail-row">
            <strong>Ürün Adı:</strong> <span><?php echo $display_name; ?></span>
        </div>
        <div class="detail-row">
            <strong>Fiyat:</strong> <span class="price-highlight"><?php echo $item_price; ?></span>
        </div>
        <div class="detail-row">
            <strong>Alıcı (Giriş Yapılan Kullanıcı):</strong> <span style="color: <?php echo $is_logged_in ? '#4CAF50' : '#f44336'; ?>; font-weight: bold;"><?php echo $buyer_username; ?></span>
        </div>
        <hr>
        <p class="warning-text"><i class="fas fa-info-circle"></i> Satın alma işlemi geri iadesizdir. Kullanıcı adınızın doğru olduğundan emin olun.</p>
    </div>

    <div class="card payment-method">
        <h2>Ödeme Yöntemi Seç</h2>
        
        <form action="#" method="POST">
            <div class="form-group payment-group">
                <input type="radio" id="pay-gpay" name="payment" value="gpay" checked>
                <label for="pay-gpay"><i class="fas fa-credit-card"></i> Kredi Kartı (Ödeme Sistemi)</label>
            </div>
            
            <div class="form-group payment-group">
                <input type="radio" id="pay-transfer" name="payment" value="transfer">
                <label for="pay-transfer"><i class="fas fa-university"></i> Havale/EFT (Manuel Onay)</label>
            </div>
            
            <div class="form-group payment-group">
                <input type="radio" id="pay-mobil" name="payment" value="mobil">
                <label for="pay-mobile"><i class="fas fa-mobile-alt"></i> Mobil Ödeme (SMS ile)</label>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-register btn-full btn-lg-confirm" <?php echo $is_logged_in ? '' : 'disabled'; ?>>Ödeme Yap ve Siparişi Onayla</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>