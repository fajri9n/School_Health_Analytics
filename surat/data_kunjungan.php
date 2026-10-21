
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesi login berakhir. Silakan login kembali.']);
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID kunjungan tidak valid.']);
    exit;
}

$sql = "
    SELECT
        k.id,
        k.tanggal,
        k.jam,
        k.jam_keluar,
        k.suhu,
        k.tekanan_darah,
        k.tindakan,
        k.catatan,
        k.kondisi,
        s.nama,
        kel.nama_keluhan,
        o.nama_obat
    FROM kunjungan k
    JOIN siswa s ON s.id = k.siswa_id
    LEFT JOIN keluhan kel ON kel.id = k.keluhan_id
    LEFT JOIN obat o ON o.id = k.obat_id
    WHERE k.id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Query pemeriksaan gagal disiapkan.']);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    http_response_code(404);
    echo json_encode(['error' => 'Data kunjungan tidak ditemukan.']);
    exit;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
