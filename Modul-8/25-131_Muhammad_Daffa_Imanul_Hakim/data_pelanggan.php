<?php
require_once 'auth.php';
khusus_owner();
require_once 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama   = trim($_POST['nama_pelanggan']);
    $jk     = $_POST['jenis_kelamin'];
    $telp   = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn, "INSERT INTO pelanggan (nama_pelanggan, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $jk, $telp, $alamat);
    mysqli_stmt_execute($stmt);
    header("Location: data_pelanggan.php");
    exit;
}

if (isset($_POST['update'])) {
    $id     = (int)$_POST['id_pelanggan'];
    $nama   = trim($_POST['nama_pelanggan']);
    $jk     = $_POST['jenis_kelamin'];
    $telp   = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn, "UPDATE pelanggan SET nama_pelanggan = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id_pelanggan = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $jk, $telp, $alamat, $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_pelanggan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM pelanggan WHERE id_pelanggan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: data_pelanggan.php");
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $edit_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h4 class="mb-3">Data Pelanggan</h4>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <?= $edit_data ? 'Edit Pelanggan' : 'Tambah Pelanggan'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="data_pelanggan.php">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id_pelanggan" value="<?= $edit_data['id_pelanggan']; ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['nama_pelanggan'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="L" <?= (($edit_data['jenis_kelamin'] ?? '') === 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?= (($edit_data['jenis_kelamin'] ?? '') === 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
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
                                <a href="data_pelanggan.php" class="btn btn-secondary">Batal</a>
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
                                    <th>Nama Pelanggan</th>
                                    <th>L/P</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_pelanggan)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                    <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?= htmlspecialchars($row['telp']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td>
                                        <a href="data_pelanggan.php?edit=<?= $row['id_pelanggan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="data_pelanggan.php?hapus=<?= $row['id_pelanggan']; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</a>
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