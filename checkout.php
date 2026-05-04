<?php
include 'config.php';
include 'auth.php';
require_pelanggan();

// Cek apakah ada data yang dikirim dari tombol "Beli Sekarang"
if (isset($_POST['proses_checkout'])) {
    $id_pelanggan = intval($_SESSION['user']['id_pelanggan']);
    $id_buku_dibeli = $_POST['id_buku'];
    $jumlah_beli = $_POST['qty'];

    mysqli_begin_transaction($conn);

    try {
        // 1. Buat Pesanan Baru
        mysqli_query($conn, "INSERT INTO pesanan (id_pelanggan, status_pesanan) VALUES ('$id_pelanggan', 'menunggu')");
        $id_pesanan = mysqli_insert_id($conn);

        // 2. Ambil data buku
        $res = mysqli_query($conn, "SELECT harga, stok FROM buku WHERE id_buku = $id_buku_dibeli FOR UPDATE");
        $buku = mysqli_fetch_assoc($res);

        if ($buku['stok'] < $jumlah_beli) {
            throw new Exception("Stok tidak cukup!");
        }

        $subtotal = $buku['harga'] * $jumlah_beli;

        // 3. Simpan Detail & Potong Stok
        mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_buku, jumlah, subtotal) 
                             VALUES ('$id_pesanan', '$id_buku_dibeli', '$jumlah_beli', '$subtotal')");
        
        mysqli_query($conn, "UPDATE buku SET stok = stok - $jumlah_beli WHERE id_buku = $id_buku_dibeli");
        
        // 4. Update Total Harga di Pesanan
        mysqli_query($conn, "UPDATE pesanan SET total_harga = '$subtotal' WHERE id_pesanan = '$id_pesanan'");

        mysqli_commit($conn);
        header("Location: pembayaran.php?id_pesanan=$id_pesanan");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal: " . $e->getMessage() . "'); window.location='index_pelanggan.php';</script>";
    }
}
?>