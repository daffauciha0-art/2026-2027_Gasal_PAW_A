<?php
require_once 'koneksi.php';

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    die("Parameter tanggal mulai dan tanggal selesai wajib diisi.");
}

// Header HTTP untuk memicu unduhan otomatis sebagai file Microsoft Excel (.xls)
$nama_file = "Rekap_Laporan_Penjualan_{$tanggal_mulai}_sd_{$tanggal_selesai}.xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");

// Query rekapitulasi harian
$sql_rekap = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
              FROM transaksi
              WHERE waktu_transaksi BETWEEN ? AND ?
              GROUP BY DATE(waktu_transaksi)
              ORDER BY tgl ASC";

$stmt_rekap = mysqli_prepare($koneksi, $sql_rekap);
mysqli_stmt_bind_param($stmt_rekap, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt_rekap);
$res_rekap = mysqli_stmt_get_result($stmt_rekap);

// Query ringkasan kumulatif
$sql_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
                     COALESCE(SUM(total), 0) AS total_semua
              FROM transaksi
              WHERE waktu_transaksi BETWEEN ? AND ?";

$stmt_total = mysqli_prepare($koneksi, $sql_total);
mysqli_stmt_bind_param($stmt_total, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt_total);
$row_total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total));

$total_pelanggan  = (int) ($row_total['jml_pelanggan'] ?? 0);
$total_pendapatan = (int) ($row_total['total_semua'] ?? 0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h3>Rekap Laporan Penjualan (<?= htmlspecialchars($tanggal_mulai) ?> sampai <?= htmlspecialchars($tanggal_selesai) ?>)</h3>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #dbeafe; font-weight: bold;">
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($res_rekap)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.') ?></td>
                    <td style="mso-number-format:'\@';"><?= date('d M Y', strtotime($row['tgl'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #dbeafe; font-weight: bold;">
                <th>Jumlah Pelanggan</th>
                <th>Jumlah Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $total_pelanggan ?> Orang</td>
                <td>Rp. <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
<?php
mysqli_stmt_close($stmt_rekap);
mysqli_stmt_close($stmt_total);
mysqli_close($koneksi);
?>