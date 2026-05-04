<?php
include 'config.php';
include 'auth.php';
require_admin();

// 1. LOGIC VALIDASI (UPDATE STATUS)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_p = $_GET['id'];
    $aksi = $_GET['aksi'];

    if ($aksi == 'setuju') {
        // Update Pembayaran jadi Lunas & Pesanan jadi Diproses
        mysqli_query($conn, "UPDATE pembayaran SET status_bayar = 'lunas' WHERE id_pesanan = $id_p");
        mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'diproses' WHERE id_pesanan = $id_p");
        header("Location: admin_pesanan.php?msg=disetujui");
        exit;
    } elseif ($aksi == 'batal') {
        // Update Pesanan jadi Batal
        mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'batal' WHERE id_pesanan = $id_p");
        header("Location: admin_pesanan.php?msg=dibatalkan");
        exit;
    }
}

// 2. AMBIL DATA PESANAN (DENGAN JOIN)
$query = "SELECT p.*, pl.nama as nama_pelanggan, pb.bukti_transfer, pb.status_bayar 
          FROM pesanan p
          JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
          LEFT JOIN pembayaran pb ON p.id_pesanan = pb.id_pesanan
          ORDER BY p.id_pesanan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Toko Buku</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Stok Buku</a>
            <a class="nav-link active" href="admin_pesanan.php">Data Pesanan</a>
        </div>
    </div>
</nav>

<div class="container">
    <h3 class="mb-4">Daftar Pesanan Masuk</h3>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Status pesanan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Bukti</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="align-middle">
                        <td>#<?= $row['id_pesanan'] ?></td>
                        <td><?= $row['nama_pelanggan'] ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <?php 
                                $s = $row['status_pesanan'];
                                $badge = ($s == 'menunggu') ? 'bg-warning' : (($s == 'diproses') ? 'bg-primary' : 'bg-danger');
                                echo "<span class='badge $badge'>$s</span>";
                            ?>
                        </td>
                        <td>
                            <span class="badge border text-dark">
                                <?= $row['status_bayar'] ?? 'belum bayar' ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['bukti_transfer']): ?>
                                <a href="uploads/<?= $row['bukti_transfer'] ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-image"></i> Lihat
                                </a>
                            <?php else: ?>
                                <small class="text-muted">N/A</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($row['status_pesanan'] == 'menunggu' && $row['status_bayar'] == 'menunggu'): ?>
                                <a href="?aksi=setuju&id=<?= $row['id_pesanan'] ?>" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg"></i> Validasi
                                </a>
                                <a href="?aksi=batal&id=<?= $row['id_pesanan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Batalkan pesanan ini?')">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            <?php elseif($row['status_pesanan'] == 'diproses'): ?>
                                <span class="text-success small fw-bold">Sudah Lunas</span>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>