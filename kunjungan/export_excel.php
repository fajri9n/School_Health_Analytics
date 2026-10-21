<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$mulai = $_GET['mulai'] ?? '';
$akhir = $_GET['akhir'] ?? '';

if (
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $mulai) ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $akhir) ||
    !checkdate((int)substr($mulai, 5, 2), (int)substr($mulai, 8, 2), (int)substr($mulai, 0, 4)) ||
    !checkdate((int)substr($akhir, 5, 2), (int)substr($akhir, 8, 2), (int)substr($akhir, 0, 4)) ||
    $mulai > $akhir
) {
    exit("Periode tanggal tidak valid.");
}

$sql = "SELECT k.tanggal, k.jam AS jam_masuk,
        k.jam_keluar, s.nama AS siswa,
        kl.nama_keluhan, o.nama_obat, k.suhu,
        k.tekanan_darah, k.tindakan, k.catatan,
        k.kondisi, u.nama AS petugas
        FROM kunjungan k
        LEFT JOIN siswa s ON k.siswa_id = s.id
        LEFT JOIN keluhan kl ON k.keluhan_id = kl.id
        LEFT JOIN obat o ON k.obat_id = o.id
        LEFT JOIN users u ON k.petugas_id = u.id
        WHERE k.tanggal BETWEEN ? AND ?
        ORDER BY k.tanggal, k.jam";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $mulai, $akhir);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

header("Content-Type: text/csv; charset=UTF-8");
header('Content-Disposition: attachment; filename="rekap_uks.csv"');

$out = fopen("php://output", "w");
fwrite($out, "\xEF\xBB\xBF");

fputcsv($out, [
    'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Nama Siswa',
    'Keluhan', 'Obat', 'Suhu', 'Tekanan Darah',
    'Tindakan', 'Catatan', 'Kondisi', 'Petugas'
], ';');

$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total++;

    $tanggal = $row['tanggal']
        ? date('d/m/Y', strtotime($row['tanggal']))
        : '-';

    $jamMasuk = $row['jam_masuk'] ?: '-';
    $jamKeluar = $row['jam_keluar'] ?: '-';

    fputcsv($out, [
        $tanggal,
        $jamMasuk,
        $jamKeluar,
        $row['siswa'] ?? '-',
        $row['nama_keluhan'] ?? '-',
        $row['nama_obat'] ?? '-',
        $row['suhu'] ?? '-',
        $row['tekanan_darah'] ?? '-',
        $row['tindakan'] ?? '-',
        $row['catatan'] ?? '-',
        $row['kondisi'] ?? '-',
        $row['petugas'] ?? '-'
    ], ';');
}

fputcsv($out, [
    'TOTAL KUNJUNGAN', $total, '', '', '', '',
    '', '', '', '', '', ''
], ';');

fclose($out);
exit;
?>