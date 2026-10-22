<?php
include "../config/koneksi.php";

$cari = trim($_GET['cari'] ?? '');

// Pesan dari proses hapus
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'hapus_berhasil') {
        echo "<script>alert('Obat berhasil dihapus.');</script>";
    }

    if ($_GET['pesan'] == 'hapus_gagal') {
        echo "<script>alert('Obat gagal dihapus.');</script>";
    }

    if ($_GET['pesan'] == 'obat_masih_digunakan') {
        echo "<script>alert('Obat tidak dapat dihapus karena masih digunakan dalam data kunjungan.');</script>";
    }
}

// Pencarian obat
if ($cari !== '') {
    $keyword = mysqli_real_escape_string($conn, $cari);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM obat
         WHERE nama_obat LIKE '%$keyword%'
         OR kategori LIKE '%$keyword%'
         ORDER BY id DESC"
    );
} else {
    $query = mysqli_query(
        $conn,
        "SELECT * FROM obat ORDER BY id DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data Obat
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>

<div class="action-bar">

    <a href="tambah.php" class="btn-tambah">
        + Tambah Obat
    </a>

    <form method="GET" action="index.php" class="search-form">
        <input
            type="text"
            name="cari"
            placeholder="Cari nama atau kategori..."
            value="<?= htmlspecialchars($cari, ENT_QUOTES, 'UTF-8') ?>"
        >

        <button type="submit" class="btn-cari">
            Cari
        </button>

        <?php if ($cari !== ''): ?>
            <a href="index.php" class="btn-reset">
                Reset
            </a>
        <?php endif; ?>
    </form>

</div>

<div class="table-wrapper">

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Tanggal Expired</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($query && mysqli_num_rows($query) > 0): ?>

            <?php $no = 1; ?>

            <?php while ($data = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++ ?></td>

                    <td>
                        <?= htmlspecialchars($data['nama_obat'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string)($data['stok'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['satuan'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['expired'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <a
                            href="edit.php?id=<?= urlencode((string)$data['id']) ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="hapus.php?id=<?= urlencode((string)$data['id']) ?>"
                            class="btn-delete"
                            onclick="return confirm('Yakin ingin menghapus obat ini?')"
                        >
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>
            <tr>
                <td colspan="7" class="empty-table">
                    <?= $cari !== ''
                        ? 'Obat yang dicari tidak ditemukan.'
                        : 'Data obat belum tersedia.' ?>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
```
