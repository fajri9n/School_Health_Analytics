<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Koneksi database belum tersedia. Periksa config/koneksi.php.");
    }

    // Periksa apakah kolom obat_id ada di tabel kunjungan
    $has_obat = false;
    $col_check = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'obat_id'");
    if ($col_check && mysqli_num_rows($col_check) > 0) {
        $has_obat = true;
    } else {
        // Coba tambahkan kolom obat_id jika belum ada di database online
        @mysqli_query($conn, "ALTER TABLE kunjungan ADD COLUMN obat_id INT(11) NULL AFTER siswa_id");
        $recheck = @mysqli_query($conn, "SHOW COLUMNS FROM kunjungan LIKE 'obat_id'");
        if ($recheck && mysqli_num_rows($recheck) > 0) {
            $has_obat = true;
        }
    }

    if ($has_obat) {
        $sql = "
            SELECT
                kunjungan.*,
                siswa.nama AS nama_siswa,
                keluhan.nama_keluhan,
                obat.nama_obat,
                users.nama AS petugas
            FROM kunjungan
            LEFT JOIN siswa
                ON kunjungan.siswa_id = siswa.id
            LEFT JOIN keluhan
                ON kunjungan.keluhan_id = keluhan.id
            LEFT JOIN obat
                ON kunjungan.obat_id = obat.id
            LEFT JOIN users
                ON kunjungan.petugas_id = users.id
            ORDER BY kunjungan.tanggal DESC, kunjungan.jam DESC
        ";
    } else {
        // Fallback aman jika tabel kunjungan di hosting belum memiliki kolom obat_id
        $sql = "
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
        ";
    }

    $query = mysqli_query($conn, $sql);
    if (!$query) {
        throw new Exception("Gagal mengeksekusi data kunjungan: " . mysqli_error($conn));
    }

} catch (Throwable $e) {
    error_log("Kunjungan index error: " . $e->getMessage());
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kendala Memuat Kunjungan</title>
        <link rel="stylesheet" href="../assets/css/dashboard.css">
    </head>
    <body>
    <header class="navbar">
        <div class="navbar-logo">
            <span class="logo-icon">🏥</span>
            <span>School Health Analytics</span>
        </div>
        <div class="navbar-title">Manajemen Data Kunjungan</div>
        <a href="../dashboard.php" class="btn-dashboard">← Dashboard</a>
    </header>

    <div style="max-width: 760px; margin: 40px auto; padding: 25px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); border-left: 6px solid #ef4444;">
        <h2 style="color: #b91c1c; margin-top: 0;">⚠️ Gagal Memuat Data Kunjungan</h2>
        <p style="color: #475569; line-height: 1.6;">Terjadi kendala saat membaca data dari database online. Detail pesan error:</p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px; font-family: monospace; color: #b91c1c; font-size: 13px; margin: 16px 0; word-break: break-word;">
            <?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="index.php" class="btn-save" style="padding: 10px 18px; font-size: 14px;">Muat Ulang Halaman</a>
            <a href="../dashboard.php" class="btn-cancel" style="padding: 10px 18px; font-size: 14px;">← Kembali ke Dashboard</a>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
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

    <div class="navbar-title">Manajemen Data Kunjungan</div>

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

<?php if (isset($_GET['status']) && $_GET['status'] === 'sukses'): ?>
    <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin: 15px 30px; border: 1px solid #bbf7d0; font-weight: 500;">
        ✓ Data kunjungan berhasil disimpan!
    </div>
<?php endif; ?>

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
        <?php if ($query && mysqli_num_rows($query) > 0): ?>
            <?php $no = 1; ?>

            <?php while ($data = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars((string)($data['tanggal'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['jam'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['jam_keluar'] ?? '-'), ENT_QUOTES, 'UTF-8') ?: '-'; ?></td>
                    <td><?= htmlspecialchars((string)($data['nama_siswa'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['nama_keluhan'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['nama_obat'] ?? '-'), ENT_QUOTES, 'UTF-8') ?: '-'; ?></td>
                    <td>
                        <?= htmlspecialchars((string)($data['suhu'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        <?= !empty($data['suhu']) ? '°C' : ''; ?>
                    </td>
                    <td><?= htmlspecialchars((string)($data['tekanan_darah'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['kondisi'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars((string)($data['petugas'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="edit.php?id=<?= (int)$data['id']; ?>" class="btn-edit">Edit</a>

                        <a href="hapus.php?id=<?= (int)$data['id']; ?>"
                           class="btn-delete"
                           onclick="return confirm('Yakin ingin menghapus data kunjungan ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>
            <tr>
                <td colspan="12">Belum ada data kunjungan.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
