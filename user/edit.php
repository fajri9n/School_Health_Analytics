<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit User</title>

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


</div>

    </div>
</div>

        </form>
<div class="section form-card">

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id']; ?>">

<label>Nama</label>

<input
type="text"
name="nama"
value="<?= $data['nama']; ?>"
required>

<label>Username</label>

<input
type="text"
name="username"
value="<?= $data['username']; ?>"
required>

<label>Password Baru</label>

<input
type="password"
name="password"
placeholder="Kosongkan jika tidak ingin mengganti password">

<label>Role</label>

<select name="role">

<option value="admin"
<?= $data['role']=="admin" ? "selected" : ""; ?>>
Administrator
</option>

<option value="user"
<?= $data['role']=="user" ? "selected" : ""; ?>>
Petugas UKS
</option>

</select>

<br><br>

<button class="btn-save">
Simpan
</button>

<a href="index.php" class="btn-cancel">
Batal
</a>

</form>

</div>

</div>

</body>

</html>