<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, dilarang mengakses halaman login
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id_user, username, password, nama, level FROM user WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $is_valid = password_verify($password, $row['password'])
                        || md5($password) === $row['password']
                        || $password === $row['password'];

            if ($is_valid) {
                session_regenerate_id(true);

                $_SESSION['login']    = true;
                $_SESSION['id_user']  = $row['id_user'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['nama']     = $row['nama'];
                $_SESSION['level']    = (int)$row['level'];

                header("Location: index.php");
                exit;
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h5 class="mb-0">Login Sistem Penjualan</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'belum_login'): ?>
                            <div class="alert alert-warning py-2">Silakan login terlebih dahulu!</div>
                        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] === 'logout'): ?>
                            <div class="alert alert-success py-2">Anda berhasil logout.</div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>