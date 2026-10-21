<?php

include "../config/koneksi.php";


if (isset($_POST['simpan'])) {

    $nama_obat = $_POST['nama_obat'];

    $kategori = $_POST['kategori'];

    $stok = $_POST['stok'];

    $satuan = $_POST['satuan'];

    $expired = $_POST['expired'];


    $query = mysqli_query(

        $conn,

        "INSERT INTO obat
        (
            nama_obat,
            kategori,
            stok,
            satuan,
            expired
        )

        VALUES
        (
            '$nama_obat',
            '$kategori',
            '$stok',
            '$satuan',
            '$expired'
        )"

    );


    if ($query) {

        header(
            "Location: index.php?pesan=tambah"
        );

        exit;

    } else {

        echo "Data obat gagal disimpan.";

        echo "<br>";

        echo mysqli_error($conn);

    }

} else {

    header(
        "Location: index.php"
    );

    exit;

}

?>