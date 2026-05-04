<?php
include 'config.php';
include 'auth.php';

$error = '';

if (isset($_SESSION['user'])) {
    if (($_SESSION['user']['role'] ?? '') === 'admin') {
        header('Location: index.php');
        exit;
    }

    header('Location: index_pelanggan.php');
    exit;
}

if (isset($_POST['register'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama.';
    } else {
        $check = mysqli_prepare($conn, 'SELECT id_user FROM `user` WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($check, 's', $email);
        mysqli_stmt_execute($check);
        $existing = mysqli_stmt_get_result($check);

        if (mysqli_fetch_assoc($existing)) {
            $error = 'Email sudah terdaftar.';
        } else {
            mysqli_begin_transaction($conn);

            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmtUser = mysqli_prepare($conn, 'INSERT INTO `user` (nama, email, password, role) VALUES (?, ?, ?, "pelanggan")');
                mysqli_stmt_bind_param($stmtUser, 'sss', $nama, $email, $hashedPassword);
                mysqli_stmt_execute($stmtUser);
                $id_user = mysqli_insert_id($conn);

                $stmtPelanggan = mysqli_prepare($conn, 'INSERT INTO pelanggan (id_user, nama) VALUES (?, ?)');
                mysqli_stmt_bind_param($stmtPelanggan, 'is', $id_user, $nama);
                mysqli_stmt_execute($stmtPelanggan);

                mysqli_commit($conn);
                header('Location: login.php?status=daftar_berhasil');
                exit;
            } catch (Throwable $e) {
                mysqli_rollback($conn);
                $error = 'Gagal mendaftar. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-1">Daftar</h3>
                    <p class="text-muted mb-4">Buat akun pelanggan untuk mulai pesan buku.</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="register" class="btn btn-primary">Daftar</button>
                            <a href="login.php" class="btn btn-outline-secondary">Sudah punya akun</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
