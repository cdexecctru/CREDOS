<?php include 'header.php'; ?>

<div class="card hero-section">
    <h1>Evurt Craft'a Hos Geldiniz!</h1>
    <p class="online-status" id="online-status-display"><i class="fas fa-users"></i> 0 Oyuncu Aktif (simdilik)</p>
    
    <div class="ip-copy-area">
        <input type="text" id="server-ip" value="evurtcraft.enderman.cloud" readonly>
        <button class="btn btn-primary" onclick="copyIP()"><i class="fas fa-copy"></i> IP Kopyala</button>
    </div>

    <a href="https://discord.gg/rQjWPkrCTQ" target="_blank" class="btn btn-primary btn-lg discord-btn"><i class="fab fa-discord"></i> Discord Sunucumuza Kat l</a>
</div>

<div class="card content-section">
    <h2>En Son Duyurular</h2>
    <div style="border: 1px solid #3d3f57; padding: 15px; border-radius: 5px; margin-top: 15px;">
        <h3>Yeni Sezon Basladi   : Evurt Yenilendi!</h3>
        <p>Sunucumuzda buyuk yenilikler ve etkinlikler sizleri bekliyor. Hemen kayit olun ve maceraya katilin!</p>
    </div>
</div>

<script>
function copyIP() {
  var copyText = document.getElementById("server-ip");
  copyText.select();
  copyText.setSelectionRange(0, 99999); // Mobil cihazlar i in
  document.execCommand("copy");
  alert("Sunucu IP'si kopyalandi : " + copyText.value);
}
</script>

<?php include 'footer.php'; ?>