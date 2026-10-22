<?php
session_start();
require_once "../config/koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    $siswa      = (int) ($_POST['siswa_id'] ?? 0);
    $obat       = (int) ($_POST['obat_id'] ?? 0);
    $keluhan    = (int) ($_POST['keluhan_id'] ?? 0);
    $tanggal    = $_POST['tanggal'] ?? '';
    $jam        = $_POST['jam'] ?? '';
    $jam_keluar = ($_POST['jam_keluar'] ?? '') ?: null;
    $suhu       = $_POST['suhu'] ?? '';
    $td         = trim($_POST['tekanan_darah'] ?? '');
    $tindakan   = trim($_POST['tindakan'] ?? '');
    $catatan    = trim($_POST['catatan'] ?? '');
    $kondisi    = trim($_POST['kondisi'] ?? '');
    $petugas    = (int) ($_SESSION['id']);

    if (
        $siswa <= 0 || $obat <= 0 || $keluhan <= 0 ||
        $tanggal === '' || $jam === '' ||
        $suhu === '' || $td === '' ||
        $tindakan === '' || $kondisi === ''
    ) {
        exit("Data wajib belum lengkap. Silakan kembali dan periksa formulir.");
    }

    $sql = "INSERT INTO kunjungan (
        siswa_id, obat_id, keluhan_id, tanggal, jam,
        jam_keluar, suhu, tekanan_darah, tindakan,
        catatan, kondisi, petugas_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiisssdssssi",
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

    echo "<h2>Gagal menyimpan data kunjungan</h2>";
    echo "<p>Pesan error: " .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
        "</p>";
    echo "<p><a href='tambah.php'>Kembali ke formulir</a></p>";
}
```
