
<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    $siswa = mysqli_query(
        $conn,
        "SELECT id, nama, kelas FROM siswa ORDER BY nama ASC"
    );

    $obat = mysqli_query(
        $conn,
        "SELECT id, nama_obat FROM obat ORDER BY nama_obat ASC"
    );

    $keluhan = mysqli_query(
        $conn,
        "SELECT id, nama_keluhan FROM keluhan ORDER BY nama_keluhan ASC"
    );
} catch (Throwable $e) {
    error_log("Gagal memuat form kunjungan: " . $e->getMessage());
    http_response_code(500);
    exit("Gagal memuat formulir. Periksa struktur tabel database.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kunjungan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">Manajemen Data Kunjungan</div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>

<div class="section form-section">
    <h2>Tambah Data Kunjungan</h2>

    <form action="proses_tambah.php" method="POST">

        <label for="siswa_id">Nama Siswa</label>
        <select name="siswa_id" id="siswa_id" required>
            <option value="">-- Pilih Siswa --</option>
            <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                <option value="<?= (int) $s['id']; ?>">
                    <?= htmlspecialchars($s['nama'], ENT_QUOTES, 'UTF-8'); ?>
                    (<?= htmlspecialchars($s['kelas'], ENT_QUOTES, 'UTF-8'); ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="keluhan_id">Keluhan</label>
        <select name="keluhan_id" id="keluhan_id" required>
            <option value="">-- Pilih Keluhan --</option>
            <?php while ($k = mysqli_fetch_assoc($keluhan)): ?>
                <option value="<?= (int) $k['id']; ?>">
                    <?= htmlspecialchars($k['nama_keluhan'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="obat_id">Obat</label>
        <select name="obat_id" id="obat_id" required>
            <option value="">-- Pilih Obat --</option>
            <?php while ($o = mysqli_fetch_assoc($obat)): ?>
                <option value="<?= (int) $o['id']; ?>">
                   
