<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-container { width: 400px; }
        .form-group { display: flex; margin-bottom: 15px; align-items: center; }
        .form-group label { width: 100px; font-weight: bold; }
        .form-group input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-group { margin-left: 100px; }
        .btn-simpan { background-color: #5cb85c; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-batal { background-color: #c9302c; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-left: 5px; display: inline-block; }
    </style>
</head>
<body>

    <h2>Tambah Data Master Supplier Baru</h2>

    <form action="" method="POST" class="form-container">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" placeholder="telp" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" placeholder="alamat" required>
        </div>
        <div class="btn-group">
            <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
            <a href="index.php" class="btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>