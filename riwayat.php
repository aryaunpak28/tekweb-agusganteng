<?php
include 'config.php';
include 'auth.php';
require_pelanggan();

$id_pelanggan = intval($_SESSION['user']['id_pelanggan']);

$query = mysqli_query(
    $conn,
    "SELECT p.id_pesanan, p.total_harga, p.status_pesanan, p.tanggal_pesan,
            pb.status_bayar, pb.bukti_transfer
     FROM pesanan p
     LEFT JOIN pembayaran pb ON pb.id_pesanan = p.id_pesanan
     WHERE p.id_pelanggan = $id_pelanggan
     ORDER BY p.id_pesanan DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Riwayat Pesanan</h3>
            <p class="text-muted mb-0"><?= htmlspecialchars($_SESSION['user']['nama']) ?></p>
        </div>
        <a href="index_pelanggan.php" class="btn btn-outline-secondary">Kembali ke Katalog</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>#<?= $row['id_pesanan'] ?></td>
                            <td><?= $row['tanggal_pesan'] ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['status_pesanan']) ?></td>
                            <td><?= htmlspecialchars($row['status_bayar'] ?? 'belum bayar') ?></td>
                            <td>
                                <?php if (($row['status_bayar'] ?? '') !== 'lunas'): ?>
                                    <a href="pembayaran.php?id_pesanan=<?= $row['id_pesanan'] ?>" class="btn btn-sm btn-primary">Bayar</a>
                                <?php else: ?>
                                    <span class="text-success small fw-semibold">Sudah dibayar</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
