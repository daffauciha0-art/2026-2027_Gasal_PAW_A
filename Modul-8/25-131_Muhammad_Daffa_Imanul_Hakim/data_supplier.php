<?php
require_once 'auth.php';
khusus_owner();
require_once 'koneksi.php';

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $nama   = trim($_POST['nama_supplier']);
    $telp   = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn, "INSERT INTO supplier (nama_supplier, telp, alamat) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nama, $telp, $alamat);
    mysqli_stmt_execute($stmt);
    header("Location: data_supplier.php");
    exit;
}

// Proses Update Data
if (isset($_POST['update'])) {
    $id     = (int)$_POST['id_supplier'];
    $nama   = trim($_POST['nama_supplier']);
    $telp   = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn, "UPDATE supplier SET nama_supplier = ?, telp = ?, alamat = ? WHERE id_supplier = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $telp, $alamat, $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_supplier.php");
    exit;
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM supplier WHERE id_supplier = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_supplier.php");
    exit;
}

// Ambil Data untuk Form Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM supplier WHERE id_supplier = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $edit_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data_supplier = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id_supplier DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Supplier - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h4 class="mb-3">Data Supplier</h4>
        <div class="row">
            <!-- Form Tambah / Edit -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <?= $edit_data ? 'Edit Supplier' : 'Tambah Supplier'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="data_supplier.php">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id_supplier" value="<?= $edit_data['id_supplier']; ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nama Supplier</label>
                                <input type="text" name="nama_supplier" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['nama_supplier'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="telp" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['telp'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($edit_data['alamat'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" name="<?= $edit_data ? 'update' : 'tambah'; ?>" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan'; ?>
                            </button>
                            <?php if ($edit_data): ?>
                                <a href="data_supplier.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Supplier</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_supplier)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_supplier']); ?></td>
                                    <td><?= htmlspecialchars($row['telp']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td>
                                        <a href="data_supplier.php?edit=<?= $row['id_supplier']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="data_supplier.php?hapus=<?= $row['id_supplier']; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus supplier ini?')">Hapus</a>
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