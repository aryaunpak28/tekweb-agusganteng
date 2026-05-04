<?php
include 'config.php';
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    mysqli_query($conn, "INSERT INTO buku (judul, harga, stok) VALUES ('$judul', '$harga', '$stok')");
    header("Location: index.php?status=sukses");
}
?>