```php
<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $query = mysqli_query($conn, "
        SELECT
            kunjungan.*,
            siswa.nama AS nama_siswa,
            keluhan.nama_keluhan,
            users.nama AS petugas
        FROM kunjungan
        LEFT JOIN siswa
            ON kunjungan.siswa_id = siswa.id
        LEFT JOIN keluhan
            ON kunjungan.keluhan_id = keluhan.id
        LEFT JOIN users
            ON kunjungan.petugas_id = users.id
        ORDER BY kunjungan.tanggal DESC, kunjungan.jam DESC
    ");
} catch (Throwable $e) {
    error_log("Kunjungan index error: " . $e->getMessage());
    http_response_code(500);
    exit("Gagal memuat data kunjungan. Periksa struktur tabel dan konfigurasi database.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data Kunjungan
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>

<div class="action-bar">
    <a href="tambah.php" class="btn-tambah">
        + Tambah Data Kunjungan
    </a>

    <a href="laporan.php" class="btn-dashboard">
        Export Laporan
    </a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Siswa</th>
                <th>Keluhan</th>
                <th>Obat</th>
                <th>Suhu</th>
                <th>Tekanan Darah</th>
                <th>Kondisi</th>
                <th>Petugas</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)):
        ?>
            <tr>
                <td><?= $no++; ?></td>

                <td>
                    <?= htmlspecialchars((string) $data['tanggal'], ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $data['jam'], ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['jam_keluar'] ?? '-'), ENT_QUOTES, 'UTF-8') ?: '-'; ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['nama_siswa'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['nama_keluhan'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>-</td>

                <td>
                    <?= htmlspecialchars((string) ($data['suhu'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                    <?= !empty($data['suhu']) ? '°C' : ''; ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['tekanan_darah'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['kondisi'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) ($data['petugas'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td>
                    <a
                        href="edit.php?id=<?= (int) $data['id']; ?>"
                        class="btn-edit">
                        Edit
                    </a>

                    <a
                        href="hapus.php?id=<?= (int) $data['id']; ?>"
                        class="btn-delete"
                        onclick="return confirm('Hapus data?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($query) === 0): ?>
            <tr>
                <td colspan="12">Belum ada data kunjungan.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div
