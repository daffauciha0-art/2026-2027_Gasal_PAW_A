<?php
require_once 'auth.php';
require_once 'koneksi.php';

$tgl_awal  = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';

$where = "";
if (!empty($tgl_awal) && !empty($tgl_akhir)) {
    $awal_safe  = mysqli_real_escape_string($conn, $tgl_awal);
    $akhir_safe = mysqli_real_escape_string($conn, $tgl_akhir);
    $where = "WHERE DATE(t.waktu_transaksi) BETWEEN '$awal_safe' AND '$akhir_safe'";
}

$query = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan, u.nama AS nama_kasir
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id_pelanggan
    LEFT JOIN user u ON t.user_id = u.id_user
    $where
    ORDER BY t.waktu_transaksi DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <?php include 'navbar.php'; ?>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Laporan Penjualan</h4>
            <button onclick="window.print()" class="btn btn-success no-print">Cetak Laporan</button>
        </div>

        <!-- Filter Tanggal -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="laporan.php" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= htmlspecialchars($tgl_awal); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= htmlspecialchars($tgl_akhir); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="laporan.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Kasir / User</th>
                            <th>Keterangan</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($query)):
                            $grand_total += (int)$row['total'];
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['waktu_transaksi'])); ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['nama_kasir'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['keterangan']); ?></td>
                            <td class="text-end">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-primary fw-bold">
                            <td colspan="5" class="text-end">Total Pendapatan</td>
                            <td class="text-end">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
