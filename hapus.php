<?php
include 'config.php';

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Jalankan query hapus
    $query = "DELETE FROM buku WHERE id_buku = $id";
    
    if (mysqli_query($conn, $query)) {
        // Balikin ke index dengan status hapus
        header("Location: index.php?pesan=hapus_berhasil");
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    // Kalau gak ada ID, tendang balik ke index
    header("Location: index.php");
}
?>