<?php
include "../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM obat WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Obat</title>

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<!-- NAVBAR -->
<header class="navbar">
    <div class="navbar-logo">
        <span class="logo-icon">🏥</span>
        <span>School Health Analytics</span>
    </div>

    <div class="navbar-title">
        Manajemen Data Siswa
    </div>

    <a href="../dashboard.php" class="btn-dashboard">
        ← Dashboard
    </a>
</header>


</div>

    </div>
</div>

        </form>
</div>

<div class="form-card">

<form action="proses_edit.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $data['id']; ?>"
>

<label>Nama Obat</label>

<input
type="text"
name="nama_obat"
value="<?php echo $data['nama_obat']; ?>"
required
>

<label>Kategori</label>

<input
type="text"
name="kategori"
value="<?php echo $data['kategori']; ?>"
required
>

<label>Stok</label>

<input
type="number"
name="stok"
value="<?php echo $data['stok']; ?>"
required
>

<label>Satuan</label>

<input
type="text"
name="satuan"
value="<?php echo $data['satuan']; ?>"
required
>

<label>Expired</label>

<input
type="date"
name="expired"
value="<?php echo $data['expired']; ?>"
required
>

<br><br>

<button
type="submit"
name="simpan"
class="btn-save"
>

Simpan

</button>

<a
href="index.php"
class="btn-cancel"
>

Batal

</a>

</form>

</div>

</div>

</body>

</html>