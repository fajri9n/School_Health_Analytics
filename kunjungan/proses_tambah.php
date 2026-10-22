<?php
session_start();
require_once "../config/koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Koneksi database tidak tersedia. Periksa config/koneksi.php.");
    }

    // Ambil dan sanitasi data input dari form
    $siswa_id_raw   = filter_input(INPUT_POST, 'siswa_id', FILTER_VALIDATE_INT);
    $keluhan_id_raw = filter_input(INPUT_POST, 'keluhan_id', FILTER_VALIDATE_INT);
    $obat_id_raw    = trim($_POST['obat_id'] ?? '');

    $tanggal        = trim($_POST['tanggal'] ?? '');
    $jam            = trim($_POST['jam'] ?? '');
    $jam_keluar_raw = trim($_POST['jam_keluar'] ?? '');
    $suhu_raw       = trim($_POST['suhu'] ?? '');
    $tekanan_darah  = trim($_POST['tekanan_darah'] ?? '');
    $tindakan       = trim($_POST['tindakan'] ?? '');
    $catatan        = trim($_POST['catatan'] ?? '');
    $kondisi        = trim($_POST['kondisi'] ?? 'Istirahat');

    // Petugas ID dari session login aktif
    $petugas_id     = (int) $_SESSION['id'];

    // Validasi field wajib
    if (!$siswa_id_raw) {
        throw new Exception("Nama siswa wajib dipilih.");
    }

    if (!$keluhan_id_raw) {
        throw new Exception("Keluhan utama wajib dipilih.");
    }

    if ($tanggal === '') {
        $tanggal = date('Y-m-d');
    }

    if ($jam === '') {
        $jam = date('H:i');
    }

    if ($tindakan === '') {
        throw new Exception("Tindakan / penanganan wajib diisi.");
    }

    // Validasi & format Kondisi
    $allowed_kondisi = ['Pulang', 'Istirahat', 'Rujuk'];
    if (!in_array($kondisi, $allowed_kondisi, true)) {
        $kondisi = 'Istirahat';
    }

    // Penanganan obat_id (opsional / NULL jika tidak diberikan obat)
    $obat_id = null;
    if ($obat_id_raw !== '' && is_numeric($obat_id_raw) && (int) $obat_id_raw > 0) {
        $obat_id = (int) $obat_id_raw;
    }

    // Penanganan jam_keluar (opsional / NULL jika belum keluar UKS)
    $jam_keluar = ($jam_keluar_raw !== '') ? $jam_keluar_raw : null;

    // Penanganan suhu tubuh (konversi koma ke titik, format string desimal atau null)
    $suhu_db = null;
    if ($suhu_raw !== '') {
        $suhu_clean = str_replace(',', '.', $suhu_raw);
        if (is_numeric($suhu_clean)) {
            $suhu_db = (string) $suhu_clean;
        }
    }

    // Validasi keberadaan petugas di tabel users agar foreign key tidak error
    $chk_user = @mysqli_query($conn, "SELECT id FROM users WHERE id = $petugas_id LIMIT 1");
    if (!$chk_user || mysqli_num_rows($chk_user) === 0) {
        $first_user = @mysqli_query($conn, "SELECT id FROM users LIMIT 1");
        if ($first_user && ($u = mysqli_fetch_assoc($first_user))) {
            $petugas_id = (int) $u['id'];
        } else {
            $petugas_id = null;
        }
    }

    // Periksa kolom tabel kunjungan secara dinamis
    $has_obat_col = false;
    $chk_obat = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'obat_id'");
    if ($chk_obat && mysqli_num_rows($chk_obat) > 0) {
        $has_obat_col = true;
    } else {
        @mysqli_query($conn, "ALTER TABLE kunjungan ADD COLUMN obat_id INT(11) NULL AFTER siswa_id");
        $recheck = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'obat_id'");
        if ($recheck && mysqli_num_rows($recheck) > 0) {
            $has_obat_col = true;
        }
    }

    $has_jk_col = false;
    $chk_jk = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'jam_keluar'");
    if ($chk_jk && mysqli_num_rows($chk_jk) > 0) {
        $has_jk_col = true;
    } else {
        @mysqli_query($conn, "ALTER TABLE kunjungan ADD COLUMN jam_keluar TIME NULL");
        $recheck_jk = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'jam_keluar'");
        if ($recheck_jk && mysqli_num_rows($recheck_jk) > 0) {
            $has_jk_col = true;
        }
    }

    // Eksekusi prepared statement dengan explicit binding sesuai kolom database yang tersedia
    if ($has_obat_col && $has_jk_col) {
        $sql = "INSERT INTO kunjungan (siswa_id, obat_id, keluhan_id, tanggal, jam, jam_keluar, suhu, tekanan_darah, tindakan, catatan, kondisi, petugas_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Gagal menyiapkan query simpan: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iiissssssssi", $siswa_id_raw, $obat_id, $keluhan_id_raw, $tanggal, $jam, $jam_keluar, $suhu_db, $tekanan_darah, $tindakan, $catatan, $kondisi, $petugas_id);

    } elseif ($has_obat_col && !$has_jk_col) {
        $sql = "INSERT INTO kunjungan (siswa_id, obat_id, keluhan_id, tanggal, jam, suhu, tekanan_darah, tindakan, catatan, kondisi, petugas_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Gagal menyiapkan query simpan: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iiisssssssi", $siswa_id_raw, $obat_id, $keluhan_id_raw, $tanggal, $jam, $suhu_db, $tekanan_darah, $tindakan, $catatan, $kondisi, $petugas_id);

    } elseif (!$has_obat_col && $has_jk_col) {
        $sql = "INSERT INTO kunjungan (siswa_id, keluhan_id, tanggal, jam, jam_keluar, suhu, tekanan_darah, tindakan, catatan, kondisi, petugas_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Gagal menyiapkan query simpan: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iissssssssi", $siswa_id_raw, $keluhan_id_raw, $tanggal, $jam, $jam_keluar, $suhu_db, $tekanan_darah, $tindakan, $catatan, $kondisi, $petugas_id);

    } else {
        $sql = "INSERT INTO kunjungan (siswa_id, keluhan_id, tanggal, jam, suhu, tekanan_darah, tindakan, catatan, kondisi, petugas_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Gagal menyiapkan query simpan: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iisssssssi", $siswa_id_raw, $keluhan_id_raw, $tanggal, $jam, $suhu_db, $tekanan_darah, $tindakan, $catatan, $kondisi, $petugas_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect ke daftar kunjungan dengan notifikasi sukses
    header("Location: index.php?status=sukses");
    exit;

} catch (Throwable $e) {
    error_log("Gagal menyimpan kunjungan: " . $e->getMessage());

    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gagal Menyimpan Kunjungan</title>
        <link rel="stylesheet" href="../assets/css/dashboard.css">
        <style>
            .error-box {
                max-width: 600px;
                margin: 60px auto;
                background: #ffffff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 16px rgba(0,0,0,0.1);
                border-left: 6px solid #ef4444;
            }
            .error-box h2 {
                color: #b91c1c;
                margin-top: 0;
                margin-bottom: 12px;
            }
            .error-box p {
                color: #475569;
                line-height: 1.6;
            }
            .error-detail {
                background: #f8fafc;
                padding: 12px;
                border-radius: 6px;
                border: 1px solid #e2e8f0;
                font-family: monospace;
                color: #b91c1c;
                font-size: 13px;
                margin: 16px 0;
                word-break: break-word;
            }
            .btn-back-form {
                display: inline-block;
                background: #2563eb;
                color: #ffffff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
                transition: background 0.2s;
            }
            .btn-back-form:hover {
                background: #1d4ed8;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>⚠️ Gagal Menyimpan Data Kunjungan</h2>
            <p>Terjadi kendala saat memproses data ke database online. Silakan periksa pesan error berikut:</p>
            <div class="error-detail">
                <?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <a href="tambah.php" class="btn-back-form">← Kembali ke Formulir</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
