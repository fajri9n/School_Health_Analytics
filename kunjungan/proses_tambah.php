<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

try {
    $siswa   = $_POST['siswa_id'] ?? '';
    $obat    = $_POST['obat_id'] ?? '';
    $keluhan = $_POST['keluhan_id'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $jam     = $_POST['jam'] ?? '';
    $jam_keluar = $_POST['jam_keluar'] ?? '';
    $suhu    = $_POST['suhu'] ?? '';
    $td      = $_POST['tekanan_darah'] ?? '';
    $tindakan = $_POST['tindakan'] ?? '';
    $catatan  = $_POST['catatan'] ?? '';
    $kondisi  = $_POST['kondisi'] ?? '';
    $petugas  = $_POST['petugas_id'] ?? '';

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
    http_response_code(500);

    echo "<h3>Gagal menyimpan data kunjungan</h3>";
    echo "<pre>" .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
        "</pre>";
}
```
