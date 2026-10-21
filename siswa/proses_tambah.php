<?php
session_start();

include "../config/koneksi.php";

/* Cek login */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}


/* Ambil data dari form */

$nis = $_POST['nis'];

$nama = $_POST['nama'];

$jenis_kelamin = $_POST['jenis_kelamin'];

$kelas = $_POST['kelas'];

$tanggal_lahir = $_POST['tanggal_lahir'];

$alamat = $_POST['alamat'];


/* Simpan data */

$query = mysqli_query(
    $conn,
    "INSERT INTO siswa
    (
        nis,
        nama,
        jenis_kelamin,
        kelas,
        tanggal_lahir,
        alamat
    )
    VALUES
    (
        '$nis',
        '$nama',
        '$jenis_kelamin',
        '$kelas',
        '$tanggal_lahir',
        '$alamat'
    )"
);


/* Cek hasil */

if ($query) {

    header(
        "Location: index.php?pesan=tambah"
    );

    exit;

} else {

    echo "Data siswa gagal disimpan!";

    echo "<br>";

    echo mysqli_error($conn);

}