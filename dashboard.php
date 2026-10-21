```php
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once "config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: auth/login.php");
    exit;
}

$nama = $_SESSION['nama'] ?? 'Pengguna';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $totalSiswa = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa")
    );

    $totalObat = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat")
    );

    $totalKunjungan = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT COUNT(*) AS total FROM kunjungan")
    );

    $totalPetugas = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT COUNT(*) AS total FROM users WHERE role = 'user'"
        )
    );

    $topKeluhan = mysqli_query($conn, "
        SELECT
            keluhan.nama_keluhan,
            COUNT(*) AS jumlah
        FROM kunjungan
        JOIN keluhan ON keluhan.id = kunjungan.keluhan_id
        GROUP BY keluhan.nama_keluhan
        ORDER BY jumlah DESC
        LIMIT 5
    ");

    $topObat = mysqli_query($conn, "
        SELECT
            obat.nama_obat,
            COUNT(*) AS jumlah
        FROM kunjungan
        JOIN obat ON obat.id = kunjungan.obat_id
        GROUP BY obat.nama_obat
        ORDER BY jumlah DESC
        LIMIT 5
    ");

    $recent = mysqli_query($conn, "
        SELECT
            kunjungan.*,
            siswa.nama
        FROM kunjungan
        JOIN siswa ON siswa.id = kunjungan.siswa_id
        ORDER BY tanggal DESC, jam DESC
        LIMIT 5
    ");

} catch (Throwable $e) {
    error_log("Dashboard error: " . $e->getMessage());

    http_response_code(500);
    echo "<!DOCTYPE html><html lang='id'><head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Error Dashboard</title></head><body>";
    echo "<h2>Terjadi kesalahan pada dashboard</h2>";
    echo "<p>Periksa struktur tabel dan konfigurasi database.</p>";
    echo "<pre>" .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
        "</pre>";
    echo "</body></html>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Health Analytics Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<button class="hamburger" id="hamburger">☰</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>School Health</h2>
        <button class="close-sidebar" id="closeSidebar">×</button>
    </div>

    <nav class="sidebar-menu">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="siswa/index.php">🧑‍🎓 Data Siswa</a>
        <a href="obat/index.php">💊 Data Obat</a>
        <a href="kunjungan/index.php">🏥 Kunjungan</a>
        <a href="surat/index.php">📄 Surat UKS</a>
        <a href="laporan/index.php">📊 Rekap Kunjungan</a>
        <a href="user/index.php">👤 User</a>
        <a href="auth/logout.php">🚪 Logout</a>
    </nav>
</aside>

<div class="main">

    <div class="header">
        <h1>School Health Analytics Dashboard</h1>
        <p>Selamat datang, <b><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></b></p>
    </div>

    <div class="cards">

        <a href="siswa/index.php" class="card">
            <h3>👨‍🎓</h3>
            <p>Total Siswa</p>
            <h1><?= (int) $totalSiswa['total'] ?></h1>
        </a>

        <a href="obat/index.php" class="card">
            <h3>💊</h3>
            <p>Total Obat</p>
            <h1><?= (int) $totalObat['total'] ?></h1>
        </a>

        <a href="kunjungan/index.php" class="card">
            <h3>🏥</h3>
            <p>Total Kunjungan</p>
            <h1><?= (int) $totalKunjungan['total'] ?></h1>
        </a>

        <a href="user/index.php" class="card">
            <h3>👨‍⚕️</h3>
            <p>Petugas</p>
            <h1><?= (int) $totalPetugas['total'] ?></h1>
        </a>

    </div>

    <div class="content">

        <div class="box">
            <h2>Top Keluhan</h2>

            <table>
                <tr>
                    <th>Keluhan</th>
                    <th>Jumlah</th>
                </tr>

                <?php while ($k = mysqli_fetch_assoc($topKeluhan)): ?>
                <tr>
                    <td><?= htmlspecialchars($k['nama_keluhan'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $k['jumlah'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="box">
            <h2>Top Obat</h2>

            <table>
                <tr>
                    <th>Obat</th>
                    <th>Jumlah</th>
                </tr>

                <?php while ($o = mysqli_fetch_assoc($topObat)): ?>
                <tr>
                    <td><?= htmlspecialchars($o['nama_obat'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $o['jumlah'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

    <div class="history">
        <h2>Riwayat Terbaru</h2>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Kondisi</th>
            </tr>

            <?php while ($r = mysqli_fetch_assoc($recent)): ?>
            <tr>
                <td><?= htmlspecialchars((string) $r['tanggal'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $r['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($r['kondisi'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<script src="assets/js/dashboard.js"></script>

</body>
</html>
```
