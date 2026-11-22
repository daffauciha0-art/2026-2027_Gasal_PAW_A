<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login, dilarang mengakses semua menu/halaman
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Proteksi khusus halaman Data Master (Level 1 = Owner)
function khusus_owner() {
    if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
        echo "<script>
                alert('Akses ditolak! Halaman ini khusus untuk Owner.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }
}
?>