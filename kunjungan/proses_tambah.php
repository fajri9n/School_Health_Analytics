<?php

include "../config/koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

try {

    $siswa      = $_POST['siswa_id'] ?? '';
    $obat       = $_POST['obat_id'] ?? '';
    $keluhan    = $_POST['keluhan_id'] ?? '';
    $tanggal    = $_POST['tanggal'] ?? '';
    $jam        = $_POST['jam'] ?? '';
    $jam_keluar = $_POST['jam_keluar'] ?? '';
    $suhu       = $_POST['suhu'] ?? '';
    $td         = $_POST['tekanan_darah'] ?? '';
    $tindakan   = $_POST['tindakan'] ?? '';
    $catatan    = $_POST['catatan'] ?? '';
    $kondisi    = $_POST['kondisi'] ?? '';
    $petugas    = $_POST['petugas_id'] ?? '';

    $sql = "INSERT INTO kunjungan (
        siswa_id,
        obat_id,
        keluhan_id,
        tanggal,
        jam,
        jam_keluar,
        suhu,
        tekanan_darah,
        tindakan,
        catatan,
        kondisi,
        petugas_id
    ) VALUES (
        ?, ?, ?, ?, ?, NULLIF(?, ''),
        ?, ?, ?, ?, ?, ?
    )";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssss",
        $siswa,
        $obat,
        $keluhan,
        $tanggal,
        $jam,
        $jam_keluar,
        $suhu,
        $td,
        $tindakan,
        $catatan,
        $kondisi,
        $petugas
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;

} catch (Throwable $e) {

    error_log("Gagal menyimpan kunjungan: " . $e->getMessage());

    http_response_code(500);

    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Gagal Menyimpan Kunjungan</title>
    </head>
    <body>
        <h2>Gagal menyimpan data kunjungan.</h2>
        <p>Periksa kembali data yang dimasukkan atau hubungi administrator.</p>
        <a href='javascript:history.back()'>Kembali ke formulir</a>
    </body>
    </html>";
}
```
