<?php
include 'config.php';
include 'auth.php';

$error = '';
$success = isset($_GET['status']) && $_GET['status'] === 'daftar_berhasil';

if (isset($_SESSION['user'])) {
    if (($_SESSION['user']['role'] ?? '') === 'admin') {
        header('Location: index.php');
        exit;
    }

    header('Location: index_pelanggan.php');
    exit;
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, 'SELECT u.id_user, u.nama, u.email, u.password, u.role, p.id_pelanggan FROM `user` u LEFT JOIN pelanggan p ON p.id_user = u.id_user WHERE u.email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'id_pelanggan' => $user['id_pelanggan'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        if ($user['role'] === 'admin') {
            header('Location: index.php');
            exit;
        }

        if (empty($user['id_pelanggan'])) {
            $stmtPelanggan = mysqli_prepare($conn, 'INSERT INTO pelanggan (id_user, nama) VALUES (?, ?)');
            mysqli_stmt_bind_param($stmtPelanggan, 'is', $user['id_user'], $user['nama']);
            mysqli_stmt_execute($stmtPelanggan);
            $_SESSION['user']['id_pelanggan'] = mysqli_insert_id($conn);
        }

        header('Location: index_pelanggan.php');
        exit;
    }

    $error = 'Email atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-1">Masuk</h3>
                    <p class="text-muted mb-4">Gunakan akun kamu untuk lanjut belanja atau masuk sebagai admin.</p>

                    <?php if ($success): ?>
                        <div class="alert alert-success">Pendaftaran berhasil. Silakan login.</div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                            <a href="register.php" class="btn btn-outline-secondary">Daftar Akun Baru</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
