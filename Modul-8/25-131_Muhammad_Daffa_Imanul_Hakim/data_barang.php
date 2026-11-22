<?php
require_once 'auth.php';
khusus_owner();
require_once 'koneksi.php';

if (isset($_POST['tambah'])) {
    $kode  = trim($_POST['kode_barang']);
    $nama  = trim($_POST['nama_barang']);
    $harga = (int)$_POST['harga'];
    $stok  = (int)$_POST['stok'];

    $stmt = mysqli_prepare($conn, "INSERT INTO barang (kode_barang, nama_barang, harga, stok) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssii", $kode, $nama, $harga, $stok);
    mysqli_stmt_execute($stmt);
    header("Location: data_barang.php");
    exit;
}

if (isset($_POST['update'])) {
    $id    = (int)$_POST['id_barang'];
    $kode  = trim($_POST['kode_barang']);
    $nama  = trim($_POST['nama_barang']);
    $harga = (int)$_POST['harga'];
    $stok  = (int)$_POST['stok'];

    $stmt = mysqli_prepare($conn, "UPDATE barang SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ? WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "ssiii", $kode, $nama, $harga, $stok, $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_barang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_barang.php");
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $edit_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data_barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h4 class="mb-3">Data Barang</h4>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <?= $edit_data ? 'Edit Barang' : 'Tambah Barang'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="data_barang.php">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id_barang" value="<?= $edit_data['id_barang']; ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['kode_barang'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['nama_barang'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['harga'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['stok'] ?? ''); ?>">
                            </div>
                            <button type="submit" name="<?= $edit_data ? 'update' : 'tambah'; ?>" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan'; ?>
                            </button>
                            <?php if ($edit_data): ?>
                                <a href="data_barang.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
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
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_barang)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['kode_barang']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?= $row['stok']; ?></td>
                                    <td>
                                        <a href="data_barang.php?edit=<?= $row['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="data_barang.php?hapus=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
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