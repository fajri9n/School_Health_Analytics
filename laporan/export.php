
<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$bulan = filter_input(INPUT_GET, 'bulan', FILTER_VALIDATE_INT);
$tahun = filter_input(INPUT_GET, 'tahun', FILTER_VALIDATE_INT);

if (!$bulan || $bulan < 1 || $bulan > 12 ||
    !$tahun || $tahun < 2000 || $tahun > 2100) {
    exit("Bulan atau tahun tidak valid.");
}

$awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$akhir = date('Y-m-d', strtotime($awal . ' +1 month'));

$sql = "SELECT s.nama, s.kelas, COUNT(k.id) AS jumlah
        FROM kunjungan k
        JOIN siswa s ON s.id = k.siswa_id
        WHERE k.tanggal >= ? AND k.tanggal < ?
        GROUP BY s.id, s.nama, s.kelas
        ORDER BY jumlah DESC, s.nama ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $awal, $akhir);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
    4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$filename = "rekap-uks-{$tahun}-" . sprintf('%02d', $bulan) . ".csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");

fputcsv($out, ['Rekap Kunjungan UKS']);
fputcsv($out, ['Periode', $namaBulan[$bulan] . ' ' . $tahun]);
fputcsv($out, []);
fputcsv($out, ['No', 'Nama Siswa', 'Kelas', 'Jumlah Kunjungan']);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($out, [
        $no++,
        $row['nama'],
        $row['kelas'],
        (int)$row['jumlah']
    ]);
}

fclose($out);
exit;
