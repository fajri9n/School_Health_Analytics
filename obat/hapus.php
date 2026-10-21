<?php

include "../config/koneksi.php";

$id = $_GET['id'];

// Cek apakah obat masih digunakan di tabel kunjungan
$cek = mysqli_query(
    $conn,
    "SELECT * FROM kunjungan WHERE obat_id='$id'"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: index.php?pesan=obat_masih_digunakan");
    exit;
}

// Hapus obat jika tidak digunakan
$query = mysqli_query(
    $conn,
    "DELETE FROM obat WHERE id='$id'"
);

if ($query) {
    header("Location: index.php?pesan=hapus_berhasil");
    exit;
} else {
    header("Location: index.php?pesan=hapus_gagal");
    exit;
}
?>