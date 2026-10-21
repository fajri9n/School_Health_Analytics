<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM kunjungan WHERE id='$id'
"));

$siswa = mysqli_query($conn,"SELECT * FROM siswa");
$obat = mysqli_query($conn,"SELECT * FROM obat");
$keluhan = mysqli_query($conn,"SELECT * FROM keluhan");
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Kunjungan</title>

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

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


</div>

    </div>
</div>

        </form>

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id" value="<?=$data['id'];?>">

<label>Nama Siswa</label>

<select name="siswa_id">

<?php while($s=mysqli_fetch_assoc($siswa)){ ?>

<option
value="<?=$s['id'];?>"

<?=$s['id']==$data['siswa_id']?'selected':'';?>>

<?=$s['nama'];?>

</option>

<?php } ?>

</select>

<br><br>

<label>Keluhan</label>

<select name="keluhan_id">

<?php while($k=mysqli_fetch_assoc($keluhan)){ ?>

<option

value="<?=$k['id'];?>"

<?=$k['id']==$data['keluhan_id']?'selected':'';?>>

<?=$k['nama_keluhan'];?>

</option>

<?php } ?>

</select>

<br><br>

<label>Obat</label>

<select name="obat_id">

<?php while($o=mysqli_fetch_assoc($obat)){ ?>

<option

value="<?=$o['id'];?>"

<?=$o['id']==$data['obat_id']?'selected':'';?>>

<?=$o['nama_obat'];?>

</option>

<?php } ?>

</select>

<br><br>

<label>Tanggal</label>

<input type="date"
name="tanggal"
value="<?=$data['tanggal'];?>">

<br><br>

<label>Jam</label>

<input
type="time"
name="jam"
value="<?=$data['jam'];?>">

<br><br>

<label>Jam Keluar</label>

<input
type="time"
name="jam_keluar"
value="<?=$data['jam_keluar'] ?? '';?>">

<label>Suhu</label>

<input
type="number"
step="0.1"
name="suhu"
value="<?=$data['suhu'];?>">

<br><br>

<label>Tekanan Darah</label>

<input
type="text"
name="tekanan_darah"
value="<?=$data['tekanan_darah'];?>">

<br><br>

<label>Tindakan</label>

<textarea
name="tindakan"><?=$data['tindakan'];?></textarea>

<br><br>

<label>Catatan</label>

<textarea
name="catatan"><?=$data['catatan'];?></textarea>

<br><br>

<label>Kondisi</label>

<select name="kondisi">

<option <?=$data['kondisi']=="Pulang"?"selected":"";?>>

Pulang

</option>

<option <?=$data['kondisi']=="Istirahat"?"selected":"";?>>

Istirahat

</option>

<option <?=$data['kondisi']=="Rujuk"?"selected":"";?>>

Rujuk

</option>

</select>

<br><br>

<button
type="submit"
class="btn-save">

Simpan

</button>

</form>

</div>

</body>

</html>