<?php
// logout.php
session_start();

// Oturumu tamamen temizle
$_SESSION = array();

// Çerezi sil (opsiyonel ama iyi uygulama)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Oturumu yok et
session_destroy();

// Ana sayfaya yönlendir
header("Location: index.php");
exit();
?>