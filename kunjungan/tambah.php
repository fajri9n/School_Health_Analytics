<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $siswa = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama ASC");
    $obat = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat ASC");
    $keluhan = mysqli_query($conn, "SELECT * FROM keluhan ORDER BY nama_keluhan ASC");
} catch (Throwable $e) {
    error_log("Tambah kunjungan: " . $e->getMessage());
    http_response_code(500);
    exit("Gagal memuat formulir kunjungan. Periksa struktur database.");
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

        <label>Nama Siswa</label>
        <select name="siswa_id" required>
            <option value="">-- Pilih Siswa --</option>
            <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                <option value="<?= (int)$s['id']; ?>">
                    <?= htmlspecialchars($s['nama'] . ' (' . $s['kelas'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label>Keluhan</label>
        <select name="keluhan_id" required>
            <option value="">-- Pilih Keluhan --</option>
            <?php while ($k = mysqli_fetch_assoc($keluhan)): ?>
                <option value="<?= (int)$k['id']; ?>">
                    <?= htmlspecialchars($k['nama_keluhan'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label>Obat</label>
        <select name="obat_id" required>
            <option value="">-- Pilih Obat --</option>
            <?php while ($o = mysqli_fetch_assoc($obat)): ?>
                <option value="<?= (int)$o['id']; ?>">
                    <?= htmlspecialchars($o['nama_obat'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label>Tanggal</label>
        <input type="date" name="tanggal"
               value="<?= date('Y-m-d'); ?>" required>

        <br><br>

        <label>Jam Masuk</label>
        <input type="time" name="jam"
               value="<?= date('H:i'); ?>" required>

        <br><br>

        <label>Jam Keluar</label>
        <input type="time" name="jam_keluar">

        <br><br>

        <label>Suhu</label>
        <input type="number" step="0.1" name="suhu"
               placeholder="36.5" required>

        <br><br>

        <label>Tekanan Darah</label>
        <input type="text" name="tekanan_darah"
               placeholder="120/80" required>

        <br><br>

        <label>Tindakan</label>
        <textarea name="tindakan" rows="3" required></textarea>

        <br><br>

        <label>Catatan</label>
        <textarea name="catatan" rows="3"></textarea>

        <br><br>

        <label>Kondisi</label>
        <select name="kondisi" required>
            <option value="Pulang">Pulang</option>
            <option value="Istirahat">Istirahat</option>
            <option value="Rujuk">Rujuk</option>
        </select>

        <input type="hidden" name="petugas_id"
               value="<?= (int)$_SESSION['id']; ?>">

        <br><br>

        <button type="submit" name="simpan" class="btn-save">
            Simpan
        </button>

        <a href="index.php" class="btn-cancel">
            Batal
        </a>

    </form>

</div>

</body>
</html>
```
