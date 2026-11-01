<?php
include 'koneksi.php';

$query = "SELECT * FROM supplier ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; width: 80%; margin-bottom: 15px; }
        .btn-tambah { background-color: #5cb85c; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #d9edf7; color: #31708f; }
        .btn-edit { background-color: #ec971f; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn-hapus { background-color: #c9302c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
    </style>
    <script>
        function konfirmasiHapus(id) {
            if (confirm("Anda yakin akan menghapus supplier ini?")) {
                window.location.href = "hapus_supplier.php?id=" + id;
            }
        }
    </script>
</head>
<body>

    <div class="header">
        <h2>Data Master Supplier</h2>
        <a href="tambah_supplier.php" class="btn-tambah">Tambah Data</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['telp']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td>
                        <a href="edit_supplier.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                        <button onclick="konfirmasiHapus(<?= $row['id']; ?>)" class="btn-hapus">Hapus</button>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data supplier.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>