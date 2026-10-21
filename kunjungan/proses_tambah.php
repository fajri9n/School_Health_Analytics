<?php

include "../config/koneksi.php";

$siswa=$_POST['siswa_id'];

$obat=$_POST['obat_id'];

$keluhan=$_POST['keluhan_id'];

$tanggal=$_POST['tanggal'];

$jam=$_POST['jam'];

$jam_keluar = $_POST['jam_keluar'] ?? '';

$suhu=$_POST['suhu'];

$td=$_POST['tekanan_darah'];

$tindakan=$_POST['tindakan'];

$catatan=$_POST['catatan'];

$kondisi=$_POST['kondisi'];

$petugas=$_POST['petugas_id'];

mysqli_query($conn,

"INSERT INTO kunjungan(

siswa_id,

obat_id,

keluhan_id,

tanggal,

jam,

jam_keluar,

suhu,

tekanan_darah,

tindakan,

catatan,

kondisi,

petugas_id

)

VALUES(

'$siswa',

'$obat',

'$keluhan',

'$tanggal',

'$jam',

NULLIF('$jam_keluar',''),

'$suhu',

'$td',

'$tindakan',

'$catatan',

'$kondisi',

'$petugas'

)"

);

header("Location:index.php");