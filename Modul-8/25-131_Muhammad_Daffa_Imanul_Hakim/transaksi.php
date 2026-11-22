<?php
require_once 'auth.php';
require_once 'koneksi.php';

// Proses Simpan Transaksi Baru
if (isset($_POST['simpan_transaksi'])) {
    $pelanggan_id = (int)$_POST['pelanggan_id'];
    $barang_id    = (int)$_POST['barang_id'];
    $qty          = (int)$_POST['qty'];
    $keterangan   = trim($_POST['keterangan']);
    $user_id      = (int)$_SESSION['id_user'];

    // Ambil harga dan stok barang
    $q_brg = mysqli_query($conn, "SELECT harga, stok FROM barang WHERE id_barang = $barang_id");
    $brg   = mysqli_fetch_assoc($q_brg);

    if ($brg && $qty > 0 && $brg['stok'] >= $qty) {
        $harga = (int)$brg['harga'];
        $total = $harga * $qty;

        $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id, user_id) VALUES (NOW(), ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siii", $keterangan, $total, $pelanggan_id, $user_id);
        mysqli_stmt_execute($stmt);
        $transaksi_id = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES ($transaksi_id, $barang_id, $harga, $qty)");
        mysqli_query($conn, "UPDATE barang SET stok = stok - $qty WHERE id_barang = $barang_id");

        header("Location: transaksi.php?status=sukses");
        exit;
    } else {
        $error = "Stok barang tidak mencukupi atau jumlah tidak valid!";
    }
}

// Hapus Transaksi
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi_detail WHERE transaksi_id = $id");
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = $id");
    header("Location: transaksi.php");
    exit;
}

$pelanggan_list = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC");
$barang_list    = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
$transaksi_list = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan, u.nama AS nama_kasir
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id_pelanggan
    LEFT JOIN user u ON t.user_id = u.id_user
    ORDER BY t.id_transaksi DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h4 class="mb-3">Data Transaksi Penjualan</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'sukses'): ?>
            <div class="alert alert-success">Transaksi berhasil disimpan!</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Tambah Transaksi</div>
                    <div class="card-body">
                        <form method="POST" action="transaksi.php">
                            <div class="mb-3">
                                <label class="form-label">Pelanggan</label>
                                <select name="pelanggan_id" class="form-select" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php while ($p = mysqli_fetch_assoc($pelanggan_list)): ?>
                                        <option value="<?= $p['id_pelanggan']; ?>"><?= htmlspecialchars($p['nama_pelanggan']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Barang</label>
                                <select name="barang_id" class="form-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php while ($b = mysqli_fetch_assoc($barang_list)): ?>
                                        <option value="<?= $b['id_barang']; ?>">
                                            <?= htmlspecialchars($b['nama_barang']); ?> (Rp <?= number_format($b['harga'], 0, ',', '.'); ?> | Stok: <?= $b['stok']; ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah (Qty)</label>
                                <input type="number" name="qty" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Pembelian tunai"></textarea>
                            </div>
                            <button type="submit" name="simpan_transaksi" class="btn btn-primary w-100">Simpan Transaksi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>Pelanggan</th>
                                    <th>Kasir/User</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($transaksi_list)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['waktu_transaksi'])); ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($row['nama_kasir'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="transaksi.php?hapus=<?= $row['id_transaksi']; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>