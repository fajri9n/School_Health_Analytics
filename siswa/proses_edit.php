<?php
include "../config/koneksi.php";

if (isset($_POST['simpan'])) {

    $id = $_POST['id'];

    $nis = $_POST['nis'];

    $nama = $_POST['nama'];

    $jenis_kelamin = $_POST['jenis_kelamin'];

    $kelas = $_POST['kelas'];

    $tanggal_lahir = $_POST['tanggal_lahir'];

    $alamat = $_POST['alamat'];

    $query = mysqli_query(
        $conn,
        "UPDATE siswa SET

        nis='$nis',

        nama='$nama',

        jenis_kelamin='$jenis_kelamin',

        kelas='$kelas',

        tanggal_lahir='$tanggal_lahir',

        alamat='$alamat'

        WHERE id='$id'"
    );

    if ($query) {

        header(
            "Location: index.php?pesan=edit"
        );

        exit;

    } else {

        echo "Data siswa gagal diperbarui.";

        echo "<br>";

        echo mysqli_error($conn);

    }

} else {

    header("Location: index.php");

    exit;

}
?>