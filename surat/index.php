
<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$sql = "SELECT su.id, su.nomor_surat, su.jenis_surat, su.tanggal_surat,
               s.nama AS siswa, su.nama_guru_piket
        FROM surat_uks su
        JOIN kunjungan k ON k.id = su.kunjungan_id
        JOIN siswa s ON s.id = k.siswa_id
        ORDER BY su.id DESC";

$result = mysqli_query($conn, $sql);
$pesan = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Surat UKS</title>
<link rel="stylesheet" href="../assets/css/dashboard.css">

<style>
.surat-wrap{max-width:1200px;margin:28px auto;padding:0 18px}
.surat-card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 4px 18px #0001}
.surat-table{width:100%;border-collapse:collapse;margin-top:18px}
.surat-table th{background:#147c43;color:#fff;text-align:left}
.surat-table th,.surat-table td{padding:12px;border-bottom:1px solid #e5e7eb}
.surat-btn{display:inline-block;background:#d9232e;color:#fff;padding:10px 15px;border-radius:7px;text-decoration:none;border:0;cursor:pointer;font:inherit}
.surat-btn.secondary{background:#475569}
.surat-btn.delete{background:#dc2626}
.surat-btn.delete:hover{background:#b91c1c}
.surat-btn.secondary:hover{background:#334155}
.table-scroll{overflow-x:auto}
.aksi{display:flex;align-items:center;gap:8px;white-space:nowrap}
.notice{padding:12px 15px;border-radius:8px;margin-top:15px}
.success{background:#dcfce7;color:#166534}
.error{background:#fee2e2;color:#991b1b}
@media(max-width:650px){.surat-card{padding:14px}.surat-table th,.surat-table td{padding:9px}}
</style>
</head>

<body>
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>
    <div class="navbar-title">Manajemen Surat UKS</div>
    <a href="../dashboard.php" class="btn-dashboard">← Dashboard</a>
</header>

<main class="surat-wrap">
<section class="surat-card">
    <h2>Daftar Surat UKS</h2>
    <p>Kelola surat izin pulang dan surat rujukan puskesmas.</p>

    <a class="surat-btn" href="buat.php">+ Buat Surat Baru</a>

    <?php if ($pesan): ?>
        <div class="notice success"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="notice error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="table-scroll">
    <table class="surat-table">
        <thead>
        <tr>
            <th>No</th>
            <th>Nomor Surat</th>
            <th>Siswa</th>
            <th>Jenis Surat</th>
            <th>Tanggal</th>
            <th>Guru Piket</th>
            <th>Aksi</th>
        </tr>
        </thead>

        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['nomor_surat']) ?></td>
                <td><?= htmlspecialchars($r['siswa']) ?></td>
                <td><?= $r['jenis_surat'] === 'pulang' ? 'Izin Pulang' : 'Rujukan Puskesmas' ?></td>
                <td><?= htmlspecialchars($r['tanggal_surat']) ?></td>
                <td><?= htmlspecialchars($r['nama_guru_piket']) ?></td>
                <td>
                    <div class="aksi">
                        <a class="surat-btn secondary"
                           href="cetak.php?id=<?= (int)$r['id'] ?>"
                           target="_blank">
                            Pratinjau / Cetak PDF
                        </a>

                        <form action="hapus.php" method="post"
                              onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" class="surat-btn delete">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Belum ada surat. Klik “Buat Surat Baru” untuk memulai.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</section>
</main>
</body>
</html>
