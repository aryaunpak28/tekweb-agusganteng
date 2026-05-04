<?php
include 'config.php';

// Ambil ID dari URL
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = $id");
$data = mysqli_fetch_assoc($result);

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $sql = "UPDATE buku SET judul='$judul', harga='$harga', stok='$stok' WHERE id_buku=$id";
    if (mysqli_query($conn, $sql)) {
        // Alihkan ke index dengan parameter status
        header("Location: index.php?pesan=update_berhasil");
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data Buku</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" value="<?= $data['judul']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="update" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>