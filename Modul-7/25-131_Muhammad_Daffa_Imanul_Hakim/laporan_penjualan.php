<?php
require_once 'koneksi.php';

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels            = [];
$data_total        = [];
$rekap             = [];
$total_pelanggan   = 0;
$total_pendapatan  = 0;
$sudah_filter      = false;
$pesan_error       = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    if ($tanggal_mulai > $tanggal_selesai) {
        $pesan_error = "Tanggal mulai tidak boleh lebih besar dari tanggal selesai.";
    } else {
        $sudah_filter = true;

        // 1. Query agregasi pendapatan harian berdasarkan rentang tanggal
        $sql_rekap = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
                      FROM transaksi
                      WHERE waktu_transaksi BETWEEN ? AND ?
                      GROUP BY DATE(waktu_transaksi)
                      ORDER BY tgl ASC";

        $stmt_rekap = mysqli_prepare($koneksi, $sql_rekap);
        mysqli_stmt_bind_param($stmt_rekap, "ss", $tanggal_mulai, $tanggal_selesai);
        mysqli_stmt_execute($stmt_rekap);
        $res_rekap = mysqli_stmt_get_result($stmt_rekap);

        while ($row = mysqli_fetch_assoc($res_rekap)) {
            $labels[]     = $row['tgl'];
            $data_total[] = (int) $row['total_harian'];
            $rekap[]      = $row;
        }
        mysqli_stmt_close($stmt_rekap);

        // 2. Query kumulatif: Jumlah Pelanggan Unik & Total Pendapatan Keseluruhan
        $sql_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
                             COALESCE(SUM(total), 0) AS total_semua
                      FROM transaksi
                      WHERE waktu_transaksi BETWEEN ? AND ?";

        $stmt_total = mysqli_prepare($koneksi, $sql_total);
        mysqli_stmt_bind_param($stmt_total, "ss", $tanggal_mulai, $tanggal_selesai);
        mysqli_stmt_execute($stmt_total);
        $res_total = mysqli_stmt_get_result($stmt_total);

        if ($row_total = mysqli_fetch_assoc($res_total)) {
            $total_pelanggan  = (int) $row_total['jml_pelanggan'];
            $total_pendapatan = (int) $row_total['total_semua'];
        }
        mysqli_stmt_close($stmt_total);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>
        Rekap Laporan Penjualan
        <?php if ($sudah_filter): ?>
            <span style="font-size: 16px; font-weight: 500; color: #475569;">
                (<?= htmlspecialchars($tanggal_mulai) ?> sampai <?= htmlspecialchars($tanggal_selesai) ?>)
            </span>
        <?php endif; ?>
    </h1>

    <!-- Form Filter Rentang Tanggal (Disembunyikan saat dicetak) -->
    <form method="GET" action="" class="filter-box no-print">
        <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                   value="<?= htmlspecialchars($tanggal_mulai) ?>" required>
        </div>
        <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                   value="<?= htmlspecialchars($tanggal_selesai) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($pesan_error) ?></div>

    <?php elseif ($sudah_filter && empty($rekap)): ?>
        <div class="alert">
            Tidak ada data transaksi pada rentang tanggal
            <strong><?= htmlspecialchars($tanggal_mulai) ?></strong> s.d.
            <strong><?= htmlspecialchars($tanggal_selesai) ?></strong>.
        </div>

    <?php elseif ($sudah_filter && !empty($rekap)): ?>
        <!-- Tombol Cetak & Ekspor Excel (Disembunyikan saat dicetak) -->
        <div class="action-buttons no-print">
            <button type="button" onclick="window.print()" class="btn btn-print">
                &#128424; Cetak
            </button>
            <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>"
               class="btn btn-excel">
                &#128196; Excel
            </a>
        </div>

        <!-- Visualisasi Grafik Batang (Chart.js) -->
        <div class="chart-wrapper">
            <canvas id="grafikPenjualan"></canvas>
        </div>

        <!-- Tabel Rekapitulasi Penjualan Harian -->
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">No</th>
                    <th>Total Pendapatan</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($rekap as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.') ?></td>
                        <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Card Ringkasan Total Kumulatif -->
        <div class="summary-cards">
            <div class="card">
                <div class="card-header">Jumlah Pelanggan</div>
                <div class="card-body"><?= number_format($total_pelanggan, 0, ',', '.') ?> Orang</div>
            </div>
            <div class="card">
                <div class="card-header">Jumlah Pendapatan</div>
                <div class="card-body">Rp. <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('grafikPenjualan').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: <?= json_encode($data_total) ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.65)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal Transaksi'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Pendapatan (Rp)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>
</body>
</html>