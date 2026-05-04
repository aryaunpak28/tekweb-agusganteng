<?php
include 'config.php';
include 'auth.php';
require_pelanggan();

// Ambil ID Pesanan dari URL
$id_pesanan = isset($_GET['id_pesanan']) ? $_GET['id_pesanan'] : '';

if (!$id_pesanan) {
    header('Location: index_pelanggan.php');
    exit;
}

$id_pelanggan = intval($_SESSION['user']['id_pelanggan']);
$cek_owner = mysqli_query($conn, "SELECT id_pesanan FROM pesanan WHERE id_pesanan = '$id_pesanan' AND id_pelanggan = '$id_pelanggan' LIMIT 1");

if (!mysqli_fetch_assoc($cek_owner)) {
    header('Location: index_pelanggan.php');
    exit;
}

// Ambil data pesanan buat nampilin nominal yang harus dibayar
$query_pesan = mysqli_query($conn, "SELECT total_harga FROM pesanan WHERE id_pesanan = '$id_pesanan'");
$data_pesan = mysqli_fetch_assoc($query_pesan);

if (isset($_POST['bayar'])) {
    $jumlah = $_POST['jumlah_bayar'];
    
    // --- LOGIC UPLOAD GAMBAR ---
    $nama_file = $_FILES['bukti_transfer']['name'];
    $tmp_name  = $_FILES['bukti_transfer']['tmp_name'];
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "BUKTI_" . $id_pesanan . "_" . time() . "." . $ekstensi;
    $tujuan    = "uploads/" . $nama_baru;

    if (!is_dir('uploads')) mkdir('uploads'); // Buat folder jika belum ada

    if (move_uploaded_file($tmp_name, $tujuan)) {
        $sql = "INSERT INTO pembayaran (id_pesanan, jumlah_bayar, status_bayar, bukti_transfer) 
                VALUES ('$id_pesanan', '$jumlah', 'menunggu', '$nama_baru')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index_pelanggan.php?status=menunggu_validasi");
            exit;
        }
    } else {
        echo "<script>alert('Gagal upload gambar!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3 text-center">
                    <h5 class="mb-0">Konfirmasi Pembayaran</h5>
                    <small>ID Pesanan: #<?= $id_pesanan ?></small>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning border-0">
                        <p class="mb-1 small text-uppercase fw-bold">Transfer Ke Rekening:</p>
                        <h5 class="mb-0 text-dark">BCA 123-456-7890</h5>
                        <p class="mb-0 small">A/N Toko Buku Digital</p>
                    </div>

                    <div class="text-center my-4">
                        <p class="text-muted mb-0">Total Tagihan:</p>
                        <h2 class="text-primary fw-bold">Rp <?= number_format($data_pesan['total_harga'], 0, ',', '.') ?></h2>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah yang Ditransfer</label>
                            <input type="number" name="jumlah_bayar" class="form-control form-control-lg" 
                                   value="<?= $data_pesan['total_harga'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bukti Transfer (JPG/PNG)</label>
                            <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                        </div>

                        <div class="d-grid gap-2 pt-3">
                            <button type="submit" name="bayar" class="btn btn-primary btn-lg">
                                Kirim Konfirmasi
                            </button>
                            <a href="index_pelanggan.php" class="btn btn-link text-muted btn-sm">Bayar Nanti</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">Pesanan Anda akan diproses setelah Admin melakukan verifikasi bukti transfer.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>