<?php
include "../config/koneksi.php";
$id=$_POST['id'];
mysqli_query($conn,
"UPDATE kunjungan SET
siswa_id='$_POST[siswa_id]',
obat_id='$_POST[obat_id]',
keluhan_id='$_POST[keluhan_id]',
tanggal='$_POST[tanggal]',
jam='$_POST[jam]',
jam_keluar=NULLIF('$_POST[jam_keluar]',''),
suhu='$_POST[suhu]',
tekanan_darah='$_POST[tekanan_darah]',
tindakan='$_POST[tindakan]',
catatan='$_POST[catatan]',
kondisi='$_POST[kondisi]'
WHERE id='$id'"
);
header("Location:index.php");