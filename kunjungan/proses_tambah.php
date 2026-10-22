```php
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
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Koneksi database tidak tersedia. Periksa config/koneksi.php.");
    }

    $siswa      = filter_input(INPUT_POST, 'siswa_id', FILTER_VALIDATE_INT);
    $obat       = filter_input(INPUT_POST, 'obat_id', FILTER_VALIDATE_INT);
    $keluhan    = filter_input(INPUT_POST, 'keluhan_id', FILTER_VALIDATE_INT);
    $tanggal    = trim($_POST['tanggal'] ?? '');
    $jam        = trim($_POST['jam'] ?? '');
    $jam_keluar = trim($_POST['jam_keluar'] ?? '');
    $suhu       = trim($_POST['suhu'] ?? '');
    $td         = trim($_POST['tekanan_darah'] ?? '');
    $tindakan   = trim($_POST['tindakan'] ?? '');
    $catatan    = trim($_POST['catatan'] ?? '');
    $kondisi    = trim($_POST['kondisi'] ?? '');
    $petugas    = (int) $_SESSION['id'];

    if (!$siswa || !$obat || !$keluhan || $tanggal === '' ||
        $jam === '' || $suhu === '' || $td === '' ||
        $tindakan === '' || $kondisi === '') {
        throw new Exception("Data wajib belum lengkap. Periksa formulir.");
    }

    $jam_keluar_db = ($jam_keluar === '') ? null : $jam_keluar;

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
        $jam_keluar_db,
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
    <html lang='
