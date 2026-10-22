<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error_msg = "";
$siswa_list = [];
$obat_list = [];
$keluhan_list = [];

try {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Koneksi database belum tersedia. Periksa konfigurasi di config/koneksi.php.");
    }

    // Pastikan tabel kunjungan memiliki kolom obat_id & jam_keluar jika belum ada
    $check_col = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'obat_id'");
    if (!$check_col || mysqli_num_rows($check_col) === 0) {
        @mysqli_query($conn, "ALTER TABLE kunjungan ADD COLUMN obat_id INT(11) NULL AFTER siswa_id");
    }

    $check_jk = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'jam_keluar'");
    if (!$check_jk || mysqli_num_rows($check_jk) === 0) {
        @mysqli_query($conn, "ALTER TABLE kunjungan ADD COLUMN jam_keluar TIME NULL");
    }

    // Ambil data master
    $res_siswa = @mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama ASC");
    if ($res_siswa) {
        while ($row = mysqli_fetch_assoc($res_siswa)) {
            $siswa_list[] = $row;
        }
    }

    $res_obat = @mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat ASC");
    if ($res_obat) {
        while ($row = mysqli_fetch_assoc($res_obat)) {
            $obat_list[] = $row;
        }
    }

    $res_keluhan = @mysqli_query($conn, "SELECT * FROM keluhan ORDER BY nama_keluhan ASC");
    if ($res_keluhan) {
        while ($row = mysqli_fetch_assoc($res_keluhan)) {
            $keluhan_list[] = $row;
        }
    }
} catch (Throwable $e) {
    error_log("Gagal memuat form kunjungan: " . $e->getMessage());
    $error_msg = "Gagal memuat data master: " . $e->getMessage();
}

$pesan_error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kunjungan - School Health Analytics</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .form-container {
            max-width: 820px;
            margin: 30px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 768px) {
            .form-grid-2, .form-grid-3 {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
        .form-field {
            margin-bottom: 14px;
        }
        .form-field label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            color: #1e293b;
        }
        .form-field input,
        .form-field select,
        .form-field textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: #f8fafc;
            color: #0f172a;
            transition: border-color 0.2s, background-color 0.2s;
            box-sizing: border-box;
            margin-top: 0;
            margin-bottom: 0;
        }
        .form-field input:focus,
        .form-field select:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
        }
        .alert-box {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        .required-star {
            color: #ef4444;
        }
        .field-helper {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
    </style>
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

<div class="form-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="color: #1e293b; font-size: 22px; margin: 0 0 4px 0;">Tambah Data Kunjungan UKS</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Catat riwayat pemeriksaan dan penanganan siswa yang berkunjung ke UKS</p>
        </div>
        <a href="index.php" class="btn-cancel" style="padding: 8px 14px; font-size: 13px;">← Kembali</a>
    </div>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert-box alert-error">
            <strong>Perhatian:</strong> <?= htmlspecialchars($pesan_error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-box alert-error">
            <strong>Error Database:</strong> <?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="proses_tambah.php" method="POST">

        <!-- Baris 1: Siswa & Keluhan -->
        <div class="form-grid-2">
            <div class="form-field">
                <label for="siswa_id">Nama Siswa <span class="required-star">*</span></label>
                <select name="siswa_id" id="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswa_list as $s): ?>
                        <option value="<?= (int) $s['id']; ?>">
                            <?= htmlspecialchars($s['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?= htmlspecialchars($s['kelas'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="field-helper">Pilih siswa yang datang memeriksakan diri</div>
            </div>

            <div class="form-field">
                <label for="keluhan_id">Keluhan Utama <span class="required-star">*</span></label>
                <select name="keluhan_id" id="keluhan_id" required>
                    <option value="">-- Pilih Keluhan --</option>
                    <?php foreach ($keluhan_list as $k): ?>
                        <option value="<?= (int) $k['id']; ?>">
                            <?= htmlspecialchars($k['nama_keluhan'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="field-helper">Keluhan atau gejala yang dirasakan</div>
            </div>
        </div>

        <!-- Baris 2: Obat -->
        <div class="form-field">
            <label for="obat_id">Obat yang Diberikan <span style="font-weight: normal; color: #64748b;">(Opsional)</span></label>
            <select name="obat_id" id="obat_id">
                <option value="">-- Tidak Diberikan Obat / Pilih Obat (Opsional) --</option>
                <?php foreach ($obat_list as $o): ?>
                    <option value="<?= (int) $o['id']; ?>">
                        <?= htmlspecialchars($o['nama_obat'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        <?php if (isset($o['stok'])): ?>
                            (Stok: <?= (int) $o['stok']; ?> <?= htmlspecialchars($o['satuan'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="field-helper">Kosongkan jika siswa hanya beristirahat atau tidak diberi obat</div>
        </div>

        <!-- Baris 3: Tanggal, Jam Masuk, Jam Keluar -->
        <div class="form-grid-3">
            <div class="form-field">
                <label for="tanggal">Tanggal Kunjungan <span class="required-star">*</span></label>
                <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d'); ?>" required>
            </div>

            <div class="form-field">
                <label for="jam">Jam Masuk <span class="required-star">*</span></label>
                <input type="time" name="jam" id="jam" value="<?= date('H:i'); ?>" required>
            </div>

            <div class="form-field">
                <label for="jam_keluar">Jam Keluar <span style="font-weight: normal; color: #64748b;">(Opsional)</span></label>
                <input type="time" name="jam_keluar" id="jam_keluar">
                <div class="field-helper">Dapat diisi saat siswa meninggalkan UKS</div>
            </div>
        </div>

        <!-- Baris 4: Suhu, Tekanan Darah, Kondisi -->
        <div class="form-grid-3">
            <div class="form-field">
                <label for="suhu">Suhu Tubuh (°C) <span class="required-star">*</span></label>
                <input type="number" step="0.1" min="30" max="45" name="suhu" id="suhu" placeholder="Contoh: 36.5" required>
            </div>

            <div class="form-field">
                <label for="tekanan_darah">Tekanan Darah <span class="required-star">*</span></label>
                <input type="text" name="tekanan_darah" id="tekanan_darah" placeholder="Contoh: 120/80" required>
            </div>

            <div class="form-field">
                <label for="kondisi">Kondisi / Status <span class="required-star">*</span></label>
                <select name="kondisi" id="kondisi" required>
                    <option value="Istirahat" selected>Istirahat di UKS</option>
                    <option value="Pulang">Pulang ke Rumah</option>
                    <option value="Rujuk">Rujuk ke Faskes</option>
                </select>
            </div>
        </div>

        <!-- Baris 5: Tindakan & Catatan -->
        <div class="form-field">
            <label for="tindakan">Tindakan / Penanganan <span class="required-star">*</span></label>
            <textarea name="tindakan" id="tindakan" rows="3" placeholder="Contoh: Diberi paracetamol 1 butir, dikompres air hangat, dan diistirahatkan di ranjang UKS" required></textarea>
        </div>

        <div class="form-field">
            <label for="catatan">Catatan Tambahan <span style="font-weight: normal; color: #64748b;">(Opsional)</span></label>
            <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan perkembangan atau pesan untuk orang tua / guru piket..."></textarea>
        </div>

        <!-- Petugas ID dari Session -->
        <input type="hidden" name="petugas_id" value="<?= (int) ($_SESSION['id'] ?? 1); ?>">

        <!-- Tombol Aksi -->
        <div class="form-actions">
            <button type="submit" name="simpan" class="btn-save" style="font-size: 15px; padding: 12px 24px;">
                💾 Simpan Kunjungan
            </button>
            <a href="index.php" class="btn-cancel" style="font-size: 15px; padding: 12px 20px;">
                Batal
            </a>
        </div>

    </form>
</div>

</body>
</html>
