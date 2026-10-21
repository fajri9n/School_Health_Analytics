<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$query = mysqli_query($conn,"
SELECT *
FROM users
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data User</title>

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<!-- NAVBAR -->
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data User
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>


<!-- ACTION BAR -->
<div class="action-bar">

    <a href="tambah.php" class="btn-tambah">
        + Tambah User
    </a>
    
</div>

    </div>
</div>

        </form>

</div>

<div class="table-wrapper">

<table>

<tr>

<th>No</th>

<th>Nama</th>

<th>Username</th>

<th>Role</th>

<th>Aksi</th>

</tr>

<?php

$no=1;

while($data=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $data['nama']; ?></td>

<td><?= $data['username']; ?></td>

<td><?= ucfirst($data['role']); ?></td>

<td>

<a
href="edit.php?id=<?= $data['id']; ?>"
class="btn-edit">

Edit

</a>

<a
href="hapus.php?id=<?= $data['id']; ?>"
class="btn-delete"
onclick="return confirm('Yakin ingin menghapus user ini?')">

Hapus

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>

</html>