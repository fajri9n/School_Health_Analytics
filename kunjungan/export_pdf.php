
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
    !checkdate((int)substr($mulai,5,2), (int)substr($mulai,8,2), (int)substr($mulai,0,4)) ||
    !checkdate((int)substr($akhir,5,2), (int)substr($akhir,8,2), (int)substr($akhir,0,4)) ||
    $mulai > $akhir
) {
    exit("Periode tanggal tidak valid.");
}

$sql = "SELECT k.tanggal, k.jam, s.nama AS siswa,
        kl.nama_keluhan, o.nama_obat, k.suhu,
        k.tekanan_darah, k.tindakan, k.catatan,
        k.kondisi, u.nama AS petugas
        FROM kunjungan k
        LEFT JOIN siswa s ON k.siswa_id=s.id
        LEFT JOIN keluhan kl ON k.keluhan_id=kl.id
        LEFT JOIN obat o ON k.obat_id=o.id
        LEFT JOIN users u ON k.petugas_id=u.id
        WHERE k.tanggal BETWEEN ? AND ?
        ORDER BY k.tanggal, k.jam";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $mulai, $akhir);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

function e($value) {
    return htmlspecialchars((string)($value ?? '-'), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekap Kunjungan UKS</title>
<style>

body {
    font: 12px Arial, sans-serif;
    color: #333;
    margin: 25px;
    background: #fff;
}

.report-header {
    background: #D92332;
    color: #fff;
    text-align: center;
    padding: 18px;
    border-radius: 6px 6px 0 0;
}

.report-header h2 {
    margin: 0 0 6px;
    letter-spacing: 1px;
}

.report-header p {
    margin: 0;
    font-size: 11px;
}

.report-info {
    background: #F1F1F1;
    padding: 12px;
    border-left: 5px solid #D92332;
    margin-bottom: 18px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
}

th {
    background: #D92332;
    color: #fff;
    padding: 8px 5px;
    border: 1px solid #B71C2A;
    text-align: left;
}

td {
    border: 1px solid #D5D5D5;
    padding: 7px 5px;
    overflow-wrap: anywhere;
}

tbody tr:nth-child(even) {
    background: #F0F0F0;
}

.actions {
    margin-bottom: 20px;
}

.actions button {
    background: #D92332;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 5px;
    cursor: pointer;
}

.signature {
    display: flex;
    justify-content: space-around;
    gap: 30px;
    margin-top: 45px;
    text-align: center;
}

.signature div {
    width: 40%;
}

.signature .space {
    height: 65px;
    border-bottom: 1px solid #888;
}

@page {
    size: A4 landscape;
    margin: 12mm;
}

@media print {
    .actions {
        display: none;
    }

    body {
        margin: 0;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    thead {
        display: table-header-group;
    }

    tr {
        break-inside: avoid;
    }
}

</style>
</head>
<body>

<div class="actions">
    <button onclick="window.print()">Cetak / Simpan PDF</button>
    <button onclick="history.back()">Kembali</button>
</div>


<div class="report-header">
    <h2>LAPORAN KUNJUNGAN UKS</h2>
    <p>TELKOM SCHOOL</p>
</div>

<div class="report-info">
    Periode <?=e($mulai)?> sampai <?=e($akhir)?>
</div>

<table>
<thead>
<tr>
    <th>No.</th>
    <th>Tanggal</th>
    <th>Jam</th>
    <th>Siswa</th>
    <th>Keluhan</th>
    <th>Obat</th>
    <th>Suhu</th>
    <th>Tekanan Darah</th>
    <th>Tindakan</th>
    <th>Kondisi</th>
    <th>Petugas</th>
</tr>
</thead>
<tbody>
<?php $no = 1; while ($d = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?=$no++?></td>
    <td><?=e($d['tanggal'])?></td>
    <td><?=e($d['jam'])?></td>
    <td><?=e($d['siswa'])?></td>
    <td><?=e($d['nama_keluhan'])?></td>
    <td><?=e($d['nama_obat'])?></td>
    <td><?=e($d['suhu'])?> °C</td>
    <td><?=e($d['tekanan_darah'])?></td>
    <td><?=e($d['tindakan'])?></td>
    <td><?=e($d['kondisi'])?></td>
    <td><?=e($d['petugas'])?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
window.addEventListener('load', () => window.print());
</script>
</body>
</html>
