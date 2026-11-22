<?php
require_once 'auth.php';
require_once 'koneksi.php';

function hitung_data($conn, $nama_tabel) {
    $cek_tabel = mysqli_query($conn, "SHOW TABLES LIKE '$nama_tabel'");
    if ($cek_tabel && mysqli_num_rows($cek_tabel) > 0) {
        $query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $nama_tabel");
        $data  = mysqli_fetch_assoc($query);
        return (int)$data['total'];
    }
    return 0;
}

$total_barang    = hitung_data($conn, 'barang');
$total_pelanggan = hitung_data($conn, 'pelanggan');
$total_transaksi = hitung_data($conn, 'transaksi');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <!-- Banner Selamat Datang -->
        <div class="alert alert-primary p-4 mb-4 rounded-3" role="alert">
            <h4 class="alert-heading fw-bold mb-2">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h4>
            <p class="mb-0">
                Anda berhasil login sebagai <strong><?= ($_SESSION['level'] == 1) ? 'Owner' : 'Kasir'; ?></strong>. 
                Silakan gunakan menu navigasi di atas untuk mengelola sistem penjualan.
            </p>
        </div>

        <!-- Kartu Ringkasan Data -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-success text-white border-0 rounded-3 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-normal mb-3">Total Data Barang</h5>
                        <h2 class="fw-bold mb-0"><?= $total_barang; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-info text-white border-0 rounded-3 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-normal mb-3">Total Pelanggan</h5>
                        <h2 class="fw-bold mb-0"><?= $total_pelanggan; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-white border-0 rounded-3 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-normal mb-3">Total Transaksi</h5>
                        <h2 class="fw-bold mb-0"><?= $total_transaksi; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>