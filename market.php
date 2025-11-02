<?php include 'header.php'; ?>

<h1 class="page-title"><i class="fas fa-store"></i> Sunucu Mağazası</h1>
<p class="page-description">Envanterinizi güçlendirecek özel eşyalar, yetenekler ve VIP paketleri burada!</p>

<div class="category-tabs">
    <a href="#" class="tab active">Tüm Ürünler</a>
    <a href="#" class="tab">VIP Paketleri</a>
    <a href="#" class="tab">Anahtarlar</a>
    <a href="#" class="tab">Özel Eşyalar</a>
</div>

<div class="product-grid">
    <div class="product-card">
        <div class="product-image" style="background-color: #3f51b5;">
            <i class="fas fa-crown fa-3x"></i>
        </div>
        <h3 class="product-title">ULTRA VIP</h3>
        <p class="product-description">Özel prefix, özel komutlar ve %25 indirim hakkı.</p>
        <div class="product-price">150 ₺</div>
        <a href="purchase.php?item=ultra-vip" class="btn btn-primary btn-full">Satın Al</a>
    </div>

    <div class="product-card">
        <div class="product-image" style="background-color: #009688;">
            <i class="fas fa-key fa-3x"></i>
        </div>
        <h3 class="product-title">Efsane Kasa Anahtarı</h3>
        <p class="product-description">Sınırlı sayıda efsanevi eşyalar kazanma şansı.</p>
        <div class="product-price">50 ₺</div>
        <a href="purchase.php?item=efsane-anahtar" class="btn btn-primary btn-full">Satın Al</a>
    </div>
    
    <div class="product-card">
        <div class="product-image" style="background-color: #ff9800;">
            <i class="fas fa-coins fa-3x"></i>
        </div>
        <h3 class="product-title">Büyük Para Paketi</h3>
        <p class="product-description">Oyunda harcamak için 10.000 oyun parası anında hesabınızda.</p>
        <div class="product-price">30 ₺</div>
        <a href="purchase.php?item=para-paketi" class="btn btn-primary btn-full">Satın Al</a>
    </div>
    
    <div class="product-card">
        <div class="product-image" style="background-color: #9c27b0;">
            <i class="fas fa-flask fa-3x"></i>
        </div>
        <h3 class="product-title">Güç İksirleri</h3>
        <p class="product-description">5 adet Hız, 5 adet Güç İksiri içeren paket.</p>
        <div class="product-price">20 ₺</div>
        <a href="purchase.php?item=iksir-paketi" class="btn btn-primary btn-full">Satın Al</a>
    </div>
    
</div>

<?php include 'footer.php'; ?>