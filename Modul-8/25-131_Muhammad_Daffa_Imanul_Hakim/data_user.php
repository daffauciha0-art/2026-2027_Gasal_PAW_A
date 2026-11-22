<?php
require_once 'auth.php';
khusus_owner();
require_once 'koneksi.php';

if (isset($_POST['tambah'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $nama     = trim($_POST['nama']);
    $alamat   = trim($_POST['alamat']);
    $hp       = trim($_POST['hp']);
    $level    = (int)$_POST['level'];

    $stmt = mysqli_prepare($conn, "INSERT INTO user (username, password, nama, alamat, hp, level) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $nama, $alamat, $hp, $level);
    mysqli_stmt_execute($stmt);
    header("Location: data_user.php");
    exit;
}

if (isset($_POST['update'])) {
    $id        = (int)$_POST['id_user'];
    $username  = trim($_POST['username']);
    $nama      = trim($_POST['nama']);
    $alamat    = trim($_POST['alamat']);
    $hp        = trim($_POST['hp']);
    $level     = (int)$_POST['level'];
    $pass_baru = trim($_POST['password']);

    if (!empty($pass_baru)) {
        $hashed = password_hash($pass_baru, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE user SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "sssssii", $username, $hashed, $nama, $alamat, $hp, $level, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE user SET username = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "ssssii", $username, $nama, $alamat, $hp, $level, $id);
    }
    mysqli_stmt_execute($stmt);
    header("Location: data_user.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id !== (int)$_SESSION['id_user']) {
        $stmt = mysqli_prepare($conn, "DELETE FROM user WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: data_user.php");
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $edit_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data_user = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h4 class="mb-3">Data User</h4>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <?= $edit_data ? 'Edit User' : 'Tambah User'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="data_user.php">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id_user" value="<?= $edit_data['id_user']; ?>">
                            <?php endif; ?>
                            <div class="mb-2">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['username'] ?? ''); ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">
                                    Password <?= $edit_data ? '<small class="text-muted">(Kosongkan jika tidak diubah)</small>' : ''; ?>
                                </label>
                                <input type="password" name="password" class="form-control" <?= $edit_data ? '' : 'required'; ?>>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required
                                       value="<?= htmlspecialchars($edit_data['nama'] ?? ''); ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($edit_data['alamat'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="hp" class="form-control"
                                       value="<?= htmlspecialchars($edit_data['hp'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level Hak Akses</label>
                                <select name="level" class="form-select" required>
                                    <option value="1" <?= (($edit_data['level'] ?? '') == 1) ? 'selected' : ''; ?>>1 - Owner</option>
                                    <option value="2" <?= (($edit_data['level'] ?? '') == 2) ? 'selected' : ''; ?>>2 - Kasir</option>
                                </select>
                            </div>
                            <button type="submit" name="<?= $edit_data ? 'update' : 'tambah'; ?>" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan'; ?>
                            </button>
                            <?php if ($edit_data): ?>
                                <a href="data_user.php" class="btn btn-secondary">Batal</a>
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
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>HP</th>
                                    <th>Level</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_user)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td><?= htmlspecialchars($row['hp']); ?></td>
                                    <td>
                                        <?php if ($row['level'] == 1): ?>
                                            <span class="badge bg-primary">1 (Owner)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">2 (Kasir)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="data_user.php?edit=<?= $row['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <?php if ($row['id_user'] != $_SESSION['id_user']): ?>
                                            <a href="data_user.php?hapus=<?= $row['id_user']; ?>" class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                        <?php endif; ?>
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