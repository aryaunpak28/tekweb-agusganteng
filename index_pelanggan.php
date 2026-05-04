<?php 
include 'config.php';
include 'auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">📚 Toko Buku Kita</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="#"><i class="bi bi-house"></i> Home</a>
            <a class="nav-link" href="riwayat.php"><i class="bi bi-receipt"></i> Pesanan Saya</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            <?php else: ?>
                <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="mb-4 text-center">Katalog Buku Terbaru</h3>
    <div class="row">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
        while($buku = mysqli_fetch_assoc($query)):
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate"><?= $buku['judul']; ?></h5>
                    <p class="text-primary fw-bold mb-1">Rp <?= number_format($buku['harga'], 0, ',', '.'); ?></p>
                    <p class="text-muted small">Tersedia: <?= $buku['stok']; ?> unit</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                        <input type="hidden" name="qty" value="1"> 
                        <button type="submit" name="proses_checkout" class="btn btn-outline-primary w-100">
                            <i class="bi bi-cart-plus"></i> Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>