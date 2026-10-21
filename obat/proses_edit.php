<?php

include "../config/koneksi.php";

$id=$_POST['id'];

$nama=$_POST['nama_obat'];

$kategori=$_POST['kategori'];

$stok=$_POST['stok'];

$satuan=$_POST['satuan'];

$expired=$_POST['expired'];

mysqli_query(

$conn,

"UPDATE obat SET

nama_obat='$nama',

kategori='$kategori',

stok='$stok',

satuan='$satuan',

expired='$expired'

WHERE id='$id'"

);

header("Location:index.php");