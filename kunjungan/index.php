<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$query = mysqli_query($conn,"
SELECT
kunjungan.*,
siswa.nama,
obat.nama_obat,
keluhan.nama_keluhan,
users.nama AS petugas

FROM kunjungan

LEFT JOIN siswa
ON kunjungan.siswa_id=siswa.id

LEFT JOIN obat
ON kunjungan.obat_id=obat.id

LEFT JOIN keluhan
ON kunjungan.keluhan_id=keluhan.id

LEFT JOIN users
ON kunjungan.petugas_id=users.id

ORDER BY tanggal DESC,jam DESC
");
?>
<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Kunjungan</title>

<link rel="stylesheet"
href="../assets/css/dashboard.css">

</head>

<body>

<!-- NAVBAR -->
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


<!-- ACTION BAR -->
<div class="action-bar">

    <a href="tambah.php" class="btn-tambah">
        + Tambah Data Kunjungan
    </a>

     <a href="laporan.php" class="btn-dashboard">
        Export Laporan
    </a>

</div>

    </div>
</div>

        </form>

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

$no=1;

while($data=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?=$no++;?></td>

<td><?=$data['tanggal'];?></td>

<td><?=$data['jam'];?></td>

<td><?=$data['jam_keluar'] ?: '-';?></td>

<td><?=$data['nama'];?></td>

<td><?=$data['nama_keluhan'];?></td>

<td><?=$data['nama_obat'];?></td>

<td><?=$data['suhu'];?> °C</td>

<td><?=$data['tekanan_darah'];?></td>

<td><?=$data['kondisi'];?></td>

<td><?=$data['petugas'];?></td>

<td>

<a
href="edit.php?id=<?=$data['id'];?>"
class="btn-edit">

Edit

</a>

<a
href="hapus.php?id=<?=$data['id'];?>"
class="btn-delete"
onclick="return confirm('Hapus data?')">

Hapus

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>