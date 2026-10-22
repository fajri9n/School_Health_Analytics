<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$cari = trim($_GET['cari'] ?? '');

// Pesan dari proses hapus
$pesan = $_GET['pesan'] ?? '';

$pesanList = [
    'hapus_berhasil' => 'Obat berhasil dihapus.',
    'hapus_gagal' => 'Obat gagal dihapus.',
    'obat_masih_digunakan' => 'Obat tidak dapat dihapus karena masih digunakan dalam data kunjungan.'
];

if (isset($pesanList[$pesan])) {
    echo '<script>alert(' .
        json_encode($pesanList[$pesan]) .
        ');</script>';
}

// Query data obat
if ($cari !== '') {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM obat
         WHERE nama_obat LIKE ?
         OR kategori LIKE ?
         ORDER BY id DESC"
    );

    $keyword = '%' . $cari . '%';

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $keyword,
        $keyword
    );

    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
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
                        <?= htmlspecialchars((string) $data['nama_obat'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string) $data['kategori'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string) $data['stok'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                   
