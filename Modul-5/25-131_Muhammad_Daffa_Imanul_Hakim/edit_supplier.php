<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data supplier berdasarkan ID
$query  = "SELECT * FROM supplier WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);
$data   = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update_query = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id='$id'";
    if (mysqli_query($koneksi, $update_query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-container { width: 400px; }
        .form-group { display: flex; margin-bottom: 15px; align-items: center; }
        .form-group label { width: 100px; font-weight: bold; }
        .form-group input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-group { margin-left: 100px; }
        .btn-update { background-color: #5cb85c; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-batal { background-color: #c9302c; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-left: 5px; display: inline-block; }
    </style>
</head>
<body>

    <h2>Edit Data Master Supplier</h2>

    <form action="" method="POST" class="form-container">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" value="<?= htmlspecialchars($data['telp']); ?>" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>" required>
        </div>
        <div class="btn-group">
            <button type="submit" name="update" class="btn-update">Update</button>
            <a href="index.php" class="btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>