
<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if ($bulan < 1 || $bulan > 12 || $tahun < 2000 || $tahun > 2100) {
    exit("Bulan atau tahun tidak valid.");
}

$awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$akhir = date('Y-m-d', strtotime($awal . ' +1 month'));

$sql = "SELECT s.id, s.nama, s.kelas, COUNT(k.id) AS jumlah
        FROM kunjungan k
        JOIN siswa s ON s.id = k.siswa_id
        WHERE k.tanggal >= ? AND k.tanggal < ?
        GROUP BY s.id, s.nama, s.kelas
        ORDER BY jumlah DESC, s.nama ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $awal, $akhir);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
$totalKunjungan = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
    $totalKunjungan += (int)$row['jumlah'];
}

$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
    4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rekap Kunjungan UKS</title>
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
.report-wrap{max-width:1200px;margin:28px auto;padding:0 18px}
.report-card{background:#fff;padding:24px;border-radius:12px;box-shadow:0 4px 18px #0001}
.filters{display:flex;gap:12px;align-items:end;flex-wrap:wrap;margin:20px 0}
.filters label{display:flex;flex-direction:column;gap:6px}
.filters select,.filters input{padding:10px;border:1px solid #cbd5e1;border-radius:7px;font:inherit}
.btn{display:inline-block;padding:10px 14px;border:0;border-radius:7px;background:#147c43;color:#fff;text-decoration:none;cursor:pointer;font:inherit}
.btn-export{background:#16803c}
.stats{display:flex;gap:16px;flex-wrap:wrap;margin:20px 0}
.stat{flex:1;min-width:180px;padding:18px;background:#f0fdf4;border-radius:10px}
.stat strong{display:block;font-size:28px;color:#147c43;margin-top:6px}
.table-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse;margin-top:15px}
th{background:#147c43;color:white;text-align:left}
th,td{padding:12px;border-bottom:1px solid #e5e7eb}
@media(max-width:600px){.report-card{padding:15px}}
</style>
</head>
<body>

<header class="navbar">
    <div class="navbar-logo">🏥 School Health Analytics</div>
    <div class="navbar-title">Rekap Kunjungan UKS</div>
    <a href="../dashboard.php" class="btn-dashboard">← Dashboard</a>
</header>

<main class="report-wrap">
<section class="report-card">
    <h2>Rekap Kunjungan Siswa</h2>
    <p>Jumlah kunjungan setiap siswa berdasarkan bulan dan tahun.</p>

    <form method="get" class="filters">
        <label>
            Bulan
            <select name="bulan">
                <?php foreach ($namaBulan as $nomor => $nama): ?>
                    <option value="<?= $nomor ?>"
                        <?= $bulan === $nomor ? 'selected' : '' ?>>
                        <?= $nama ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Tahun
            <input type="number" name="tahun"
                   min="2000" max="2100"
                   value="<?= $tahun ?>" required>
        </label>

        <button class="btn" type="submit">Tampilkan</button>

        <a class="btn btn-export"
           href="export.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>">
            Export Excel
        </a>
    </form>

    <div class="stats">
        <div class="stat">
            Total Kunjungan
            <strong><?= $totalKunjungan ?></strong>
        </div>
        <div class="stat">
            Siswa Berkunjung
            <strong><?= count($data) ?></strong>
        </div>
    </div>

    <h3>Periode: <?= $namaBulan[$bulan] . ' ' . $tahun ?></h3>

    <div class="table-scroll">
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jumlah Kunjungan</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data): ?>
            <?php foreach ($data as $i => $siswa): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($siswa['nama']) ?></td>
                <td><?= htmlspecialchars($siswa['kelas'] ?? '-') ?></td>
                <td><?= (int)$siswa['jumlah'] ?> kali</td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada kunjungan pada periode ini.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</section>
</main>
</body>
</html>
