<?php 
include 'config.php';
include 'auth.php';
require_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-book"></i> Toko Buku Digital</a>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-light btn-sm" href="admin_pesanan.php">
                <i class="bi bi-bag-check"></i> Manajemen Pesanan
            </a>
            <a class="btn btn-outline-light btn-sm" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<?php if(isset($_GET['pesan'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php 
            if($_GET['pesan'] == "update_berhasil") echo "Data buku berhasil diperbarui!";
            if($_GET['pesan'] == "hapus_berhasil") echo "Data buku telah dihapus!";
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">Manajemen Stok Buku</h5>
                            <p class="text-muted mb-3">Tambah, ubah, dan hapus data buku di katalog.</p>
                        </div>
                        <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                    </div>
                    <a href="#stokBuku" class="btn btn-primary btn-sm">Lihat Data Buku</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">Manajemen Pesanan</h5>
                            <p class="text-muted mb-3">Validasi pembayaran dan ubah status pesanan pelanggan.</p>
                        </div>
                        <i class="bi bi-bag-check fs-2 text-success"></i>
                    </div>
                    <a href="admin_pesanan.php" class="btn btn-success btn-sm">Buka Pesanan Masuk</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Stok Buku</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-lg"></i> Tambah Buku
            </button>
        </div>
        <div class="card-body" id="stokBuku">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
                        while($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td><strong><?= $row['judul']; ?></strong></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge <?= $row['stok'] < 5 ? 'bg-danger' : 'bg-success'; ?>">
                                    <?= $row['stok']; ?> unit
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id_buku']; ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus buku ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="tambah.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Input Buku Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Belajar PHP Dasar">
          </div>
          <div class="mb-3">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Stok Awal</label>
            <input type="number" name="stok" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="submit" class="btn btn-primary">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>